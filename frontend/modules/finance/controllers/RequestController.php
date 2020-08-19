<?php

namespace frontend\modules\finance\controllers;

use Yii;
use common\models\apiservice\Notificationrecipient;
use common\models\finance\Request;
use common\models\finance\Requestattachment;
use common\models\finance\Requesttype;
use common\models\finance\RequestSearch;
use common\models\procurement\Disbursement;
use common\models\sec\Blockchain;
use common\models\system\Comment;
use common\models\system\CommentSearch;

use kartik\mpdf\Pdf;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\db\Query;
/**
 * RequestController implements the CRUD actions for Request model.
 */
class RequestController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Request models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RequestSearch();
        if(Yii::$app->user->identity->username != 'Admin')
            $searchModel->created_by =  Yii::$app->user->identity->user_id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    /**
     * Lists all Request models.
     * @return mixed
     */
    public function actionVerifyindex()
    {
        $searchModel = new RequestSearch();
        $searchModel->status_id = Request::STATUS_SUBMITTED;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('verifyindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    /**
     * Lists all Request models.
     * @return mixed
     */
    public function actionValidateindex()
    {
        $searchModel = new RequestSearch();
        $searchModel->status_id = Request::STATUS_VERIFIED;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('validateindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    /**
     * Lists all Request models.
     * @return mixed
     */
    public function actionProcessingindex()
    {
        $searchModel = new RequestSearch();
        $searchModel->status_id = Request::STATUS_VALIDATED;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('processingindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    /**
     * Displays a single Request model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id); 
        
        $params = $this->checkAttachments($model);
        
        $request_status = $this->checkStatus($model->status_id);
        
        $attachmentsDataProvider = new ActiveDataProvider([
            'query' => $model->getAttachments(),
            'pagination' => false,
            /*'sort' => [
                'defaultOrder' => [
                    'availability' => SORT_ASC,
                    'item_category_id' => SORT_ASC,
                    //'title' => SORT_ASC, 
                ]
            ],*/
        ]);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('kv-detail-success', 'Request Updated!');
        }
        
        return $this->render('view', [
            'model' => $model,
            'attachmentsDataProvider' => $attachmentsDataProvider,
            'request_status' => $request_status,
            'params' => $params,
        ]);
    }

    /**
     * Creates a new Request model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Request();
        date_default_timezone_set('Asia/Manila');
        $model->request_date=date("Y-m-d H:i:s");
        if ($model->load(Yii::$app->request->post())) {
            
            $model->request_number = Request::generateRequestNumber();
            $model->created_by = Yii::$app->user->identity->user_id;
            
            if($model->save(false))
                return $this->redirect(['view', 'id' => $model->request_id]);
            
        }elseif (Yii::$app->request->isAjax) {
            return $this->renderAjax('_form', [
                        'model' => $model,
            ]);
        } else {
            return $this->render('_form', [
                        'model' => $model,
            ]);
        }
    }
    
    public function actionViewattachments()
    {
        $model = $this->findModel($_GET['id']);
        
        if (Yii::$app->request->post()) {
            foreach($model->requesttype->requesttypeattachments as $requesttypeattachment)
            {
                $modelRequestattachment = new Requestattachment();
                $modelRequestattachment->request_id = $model->request_id;
                //$modelRequestattachment->name = $requesttypeattachment->attachment->name;
                $modelRequestattachment->attachment_id = $requesttypeattachment->attachment_id;
                $modelRequestattachment->save(false);
            }
            return $this->redirect(['view', 'id' => $model->request_id]);  
        }
        if (Yii::$app->request->isAjax) {
                return $this->renderAjax('_info', ['model'=>$model]);   
        }
        
    }

    /**
     * Updates an existing Request model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->request_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Request model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Request model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Request the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Request::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public function actionUpdateparticulars()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        $response = Requesttype::findOne($_POST['requestTypeId']);
        if($response)
            return $response;
    }
    
    public function actionUploadattachment($id)
    {
        //Yii::$app->params['uploadPath'] = Yii::$app->basePath . '/uploads/';
        $model = Requestattachment::findOne($id);
        
        if (Yii::$app->request->post()) {
            $random = Yii::$app->security->generateRandomString(40);
            $model->pdfFile = UploadedFile::getInstance($model, 'pdfFile');
            
            //$path = 'uploads/finance/request/' . $model->request->request_number.'/';
            $path = Yii::getAlias('@uploads') . "/finance/request/" . $model->request->request_number;
            if(!file_exists($path)){
                mkdir($path, 0755, true);
                $indexFile = fopen($path.'/index.php', 'w') or die("Unable to open file!");
            }
                
            $model->pdfFile->saveAs( $path ."/". $model->request_attachment_id . $random . '.' . $model->pdfFile->extension);
            //$model->pdfFile->saveAs('uploads/finance/request/' . $model->request->request_number.'/'. $model->request_attachment_id . $random . '.' . $model->pdfFile->extension);
            $model->filename = $model->request_attachment_id . $random . '.' . $model->pdfFile->extension;
            $model->save(false);
            
            Yii::$app->session->setFlash('success', 'Document Successfully Uploaded!');
            
            return $this->redirect(['view?id='.$model->request_id]);
        }
        
        if (Yii::$app->request->isAjax) {
                return $this->renderAjax('_upload', ['model'=>$model]);   
        }else {
            return $this->render('_upload', [
                        'model' => $model,
            ]);
        }
    }
    
    public function actionMarkverified($id)
    {
        $model = Requestattachment::findOne($id);
        
        if (Yii::$app->request->post()) {
            $model->status_id = 10;
            if($model->save())
                Yii::$app->session->setFlash('success', 'Attachment has been Verified!');
            
            return $this->redirect(['view?id='.$model->request_id]);
        }
    }
    
    public function actionDeleteattachment(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        $model = Requestattachment::findOne($_POST['key']);
        $file = 'uploads/finance/request/' . $model->request->request_number.'/'. $model->filename;
        
        if(unlink($file))
        {
            $model->filename = '';
            $model->save(false);
            return 'File deleted!';
        }else{
            return 'File was not deleted!';
        }
    }
    
    function checkStatus($status_id)
    {
        switch ($status_id) {
            case 20:
                $msg = 'Submitted';
                $alert = 'alert-info';
                break;
            case 30:
                $msg = 'Verified';
                $alert = 'alert-info';
                break;
            case 40:
                $msg = 'Validated';
                $alert = 'alert-info';
                break;
            case 50:
                $msg = 'Certified Allotment Available';
                $alert = 'alert-info';
                break;
            case 55:
                $msg = 'Alloted';
                $alert = 'alert-info';
                break;
            case 60:
                $msg = 'Certified Funds Available';
                $alert = 'alert-info';
                break;
            case 65:
                $msg = 'Charged';
                $alert = 'alert-info';
                break;
            case 70:
                $msg = 'Approved for Disbursement';
                $alert = 'alert-info';
                break;
            case 80:
                $msg = 'Completed';
                $alert = 'alert-success';
                break;
            default:
                $msg = '---';
                $alert = 'alert-info';
        }

        return [
            'msg' => $msg,
            'alert' => $alert,
        ];
    }
    
    function checkAttachments($modelAttachments)
    {
        $files = 0;
        $count = count($modelAttachments->attachments);
        $txt = '';
        $result = 0;
        if($count){
            foreach($modelAttachments->attachments as $attachment){
                $attachment_exist = Requestattachment::hasAttachment($attachment->request_attachment_id);
                //$txt .= '-'.$attachment->request_attachment_id; // debugging purposes

                $files += $attachment_exist;
                $txt .= '-'.$attachment_exist;
            }
            $result = $count - $files; 
            switch ($result) {
                case false:
                    $btnClass = 'btn btn-success';
                    $btnStatus = true;
                    break;
                case ($result < $count):
                    $btnClass = 'btn btn-warning';
                    $btnStatus = false;
                    break;
                case ($result == $count):
                    $btnClass = 'btn btn-danger';
                    $btnStatus = false;
                    break;
            }
        }else{
            $btnClass = 'btn btn-danger';
            $btnStatus = false;
        }
        
        return [
            'trace' => $txt,
            'btnClass' => $btnClass,
            'btnStatus' => $btnStatus,
            'requiredDocs' => $count,
            'files' => $files,
            'result' => $result
        ];
    }
    
    function checkAttachmentsVerified($modelAttachments)
    {
        $files = 0;
        $count = count($modelAttachments->attachments);
        $txt = '';
        $result = 0;
        
        $notVerified = Requestattachment::find()->where(['request_id' => $modelAttachments->request_id, 'status_id' => 0])->all();
        if($notVerified)
            return false;
        else
            return true;
    }
    
    function actionSubmitforverification()
    {
        $model = $this->findModel($_GET['id']);
        
        $params = $this->checkAttachments($model);
        $eligibleToSubmit = $params['btnStatus'];
        
        if($eligibleToSubmit){
            if (Yii::$app->request->post()) {
                $model->status_id = Request::STATUS_SUBMITTED; //20
                if($model->save(false)){
                    
                    $index = $model->request_id;
                    $scope = 'Request';
                    $data = $model->request_number.':'.$model->request_date.':'.$model->request_type_id.':'.$model->payee_id.':'.$model->particulars.':'.$model->amount.':'.$model->status_id;
                    
                    $block = Blockchain::createBlock($index, $scope, $data);
                    
                    $content = 'Request Number: '.$model->request_number.PHP_EOL;
                    $content .= 'Payee: '.$model->creditor->name.PHP_EOL;
                    $content .= 'Amount: '.$model->amount.PHP_EOL.PHP_EOL;
                    $content .= 'Particulars: '.PHP_EOL.$model->particulars;
                    $recipient = Notificationrecipient::find()->where(['status_id' => $model->status_id])->one();
                    
                    Yii::$app->Notification->sendSMS('', 2, $recipient->primary->sms.','.$recipient->secondary->sms, 'Request for Verification', $content, 'FAIMS', $this->module->id, $this->action->id);
                    
                    Yii::$app->Notification->sendEmail('', 2, $recipient->primary->email.','.$recipient->secondary->email, 'Request for Verification', $content, 'FAIMS', $this->module->id, $this->action->id);
                    
                    Yii::$app->session->setFlash('success', 'Request submitted for Verification!');
                }else{
                    Yii::$app->session->setFlash('success', $model->getErrors());                 
                }
                return $this->redirect(['view', 'id' => $model->request_id]);
                    
            }

            if (Yii::$app->request->isAjax) {
                    return $this->renderAjax('_submitforverification', ['model'=>$model]);   
            }else {
                return $this->render('_submitforverification', [
                            'model' => $model,
                ]);
            }
        }else{
            if (Yii::$app->request->isAjax) {
                    return $this->renderAjax('_noteligible', ['model'=>$model]);   
            }
        }
    }
    
    function actionSubmitforvalidation()
    {
        $model = $this->findModel($_GET['id']);
        
        $eligibleToSubmit = $this->checkAttachmentsVerified($model);
        //$eligibleToSubmit = $params['btnStatus'];
        
        if(Yii::$app->user->can('access-finance-verification') && $eligibleToSubmit){
            if (Yii::$app->request->post()) {
                $model->status_id = Request::STATUS_VERIFIED; //30
                if($model->save(false)){
                    
                    $index = $model->request_id;
                    $scope = 'Request';
                    $data = $model->request_number.':'.$model->request_date.':'.$model->request_type_id.':'.$model->payee_id.':'.$model->particulars.':'.$model->amount.':'.$model->status_id;
                    $block = Blockchain::createBlock($index, $scope, $data);
                    
                    $content = 'Request Number: '.$model->request_number.PHP_EOL;
                    $content .= 'Payee: '.$model->creditor->name.PHP_EOL;
                    $content .= 'Amount: '.$model->amount.PHP_EOL.PHP_EOL;
                    $content .= 'Particulars: '.PHP_EOL.$model->particulars;
                    $recipient = Notificationrecipient::find()->where(['division_id' => $model->division_id, 'status_id' => $model->status_id])->one();
                    
                    Yii::$app->Notification->sendSMS('', 2, $recipient->primary->sms, 'Request for Validation', $content, 'FAIMS', $this->module->id, $this->action->id);
                    
                    Yii::$app->Notification->sendEmail('', 2, $recipient->primary->email, 'Request for Verification', $content, 'FAIMS', $this->module->id, $this->action->id);
                    
                    Yii::$app->session->setFlash('success', 'Request Successfully Submitted!');
                }else{
                    Yii::$app->session->setFlash('success', $model->getErrors());                 
                }
                return $this->redirect(['view', 'id' => $model->request_id]);
                    
            }

            if (Yii::$app->request->isAjax) {
                    return $this->renderAjax('_submitforvalidation', ['model'=>$model]);   
            }else {
                return $this->render('_submitforvalidation', [
                            'model' => $model,
                ]);
            }
        }else{
            if (Yii::$app->request->isAjax) {
                    return $this->renderAjax( Yii::$app->user->can('access-finance-verification') ? '_noteligibleforvalidation' : '_notallowed', ['model'=>$model]);   
            }
        }
    }
    
    function actionValidate()
    {
        $model = $this->findModel($_GET['id']);
        
        if(Yii::$app->user->can('access-finance-validation')){
            if (Yii::$app->request->post()) {
                $model->status_id = ($model->obligation_type_id == 1) ? Request::STATUS_VALIDATED : Request::STATUS_ALLOTTED ; //40 : 55
                if($model->save(false)){
                    
                    $index = $model->request_id;
                    $scope = 'Request';
                    $data = $model->request_number.':'.$model->request_date.':'.$model->request_type_id.':'.$model->payee_id.':'.$model->particulars.':'.$model->amount.':'.$model->status_id;
                    Blockchain::createBlock($index, $scope, $data);
                    
                    $content = 'Request Number: '.$model->request_number.PHP_EOL;
                    $content .= 'Payee: '.$model->creditor->name.PHP_EOL;
                    $content .= 'Amount: '.$model->amount.PHP_EOL.PHP_EOL;
                    $content .= 'Particulars: '.PHP_EOL.$model->particulars;
                    $recipient = Notificationrecipient::find()->where(['status_id' => $model->status_id])->one();
                    
                    Yii::$app->Notification->sendSMS('', 2, $recipient->primary->sms, 'Request for Obligation', $content, 'FAIMS', $this->module->id, $this->action->id);
                    
                    Yii::$app->Notification->sendEmail('', 2, $recipient->primary->email, 'Request for Verification', $content, 'FAIMS', $this->module->id, $this->action->id);
                    
                    Yii::$app->session->setFlash('success', 'Request Successfully Validated!');
                }else{
                    Yii::$app->session->setFlash('success', $model->getErrors());                 
                }
                return $this->redirect(['view', 'id' => $model->request_id]);
                    
            }

            if (Yii::$app->request->isAjax) {
                    return $this->renderAjax('_validate', ['model'=>$model]);   
            }else {
                return $this->render('_validate', [
                            'model' => $model,
                ]);
            }
        }else{
            if (Yii::$app->request->isAjax) {
                    return $this->renderAjax('_notallowed', ['model'=>$model]);   
            }
        }
    }
    
    public static function createBlock($index)
    {
        $request = Request::findOne($index);

        $index = $request->request_id;
        $scope = 'Request';
        $timestamp = time();
        $data = $request->request_number.':'.$request->request_date.':'.$request->request_type_id.':'.$request->payee_id.':'.$request->particulars.':'.$request->amount.':'.$request->status_id;
        
        $block = new Blockchain();
        $block->index_id = $index;
        $block->scope = $scope;
        $block->timestamp = $timestamp;
        $block->data = $data;
        $block->hash = $block->calculateHash();
        $block->nonce = $timestamp;

        $block->save();
    }
    
    function actionComments()
    {
        $searchModel = new CommentSearch();
        $searchModel->component_id = Comment::COMPONENT_ATTACHMENT;
        $searchModel->record_id = $_GET['record_id'];
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        //$comments = Comment::find()->where(['component_id' => Comment::COMPONENT_ATTACHMENT, 'record_id' => $_GET['record_id']])->all();

        if (Yii::$app->request->isAjax) {
                return $this->renderAjax('_comments', [
                    'searchModel' => $searchModel,
                    'dataProvider'=>$dataProvider
                ]);   
        }else {
            return $this->render('_comments', [
                        //'model' => $model,
            ]);
        }
    }
    
    function actionMigrate()
    {
        $model1 = Disbursement::find()->orderBy(['dv_date' => SORT_ASC])->all();
        return $this->render('migrate', [
                    'model' => $model1,
        ]); 
    }
    
    
}
