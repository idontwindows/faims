<?php

namespace frontend\modules\procurement\controllers;
use common\models\procurement\Purchaserequestdetails;
use Yii;
use common\modules\admin\models\User;
use common\models\procurement\Purchaserequest;
use common\models\procurement\PurchaserequestSearch;

use yii\helpers\Html;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use kartik\mpdf\Pdf;
use yii\helpers\Url;
$session = Yii::$app->session;
$model = new Purchaserequest();

/**
 * PurchaserequestController implements the CRUD actions for Purchaserequest model.
 */
class PurchaserequestController extends Controller
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
     * Lists all Purchaserequest models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PurchaserequestSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
                return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                ]);
    }

    /***
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView()
    {
        $request = Yii::$app->request;
        if($request->get('id') && $request->get('view')) {
            $id = $request->get('id');
            $model = $this->findModel($id);
            $prdetails = $this->getprDetails($model->purchase_request_id);
            return $this->renderAjax('_view', [
                'model' => $model,
                'prdetails' =>$prdetails,
            ]);
        }
    }

    public function actionReportpr($id) {
            $request = Yii::$app->request;
            $id = $request->get('id');
            $model = $this->findModel($id);
            $prdetails = $this->getprDetails($model->purchase_request_id);
            $content = $this->renderPartial('_report', ['prdetails'=> $prdetails,'model'=>$model]);
            $pdf = new Pdf();
            $pdf->format = pdf::FORMAT_A4;
            $pdf->orientation = Pdf::ORIENT_PORTRAIT;
            $pdf->destination =  $pdf::DEST_BROWSER;
            $pdf->content  = $content;
            $pdf->cssFile = '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css';
            $pdf->cssInline = 'body {font-size:}.kv-heading-1{font-size:18px}.nospace-border{border:0px;}.no-padding{ padding:0px;}.print-container{font-size:11px;font-family:Arial,Helvetica Neue,Helvetica,sans-serif;}h6 {  }';
            $pdf->marginFooter=5;

            $requested_by="";
            $requested_by_position="";
            $approved_by="";
            $approved_by_position="";

            foreach ($prdetails as $pr) {
                $requested_by = $pr["requested_by"];
                $requested_by_position = $pr["requested_by_position"];
                $approved_by = $pr["approved_by"];
                $approved_by_position = $pr["approved_by_position"];
            }


            $pdf->marginTop = 75;
            $pdf->marginBottom = 100;

            $headers= '<div style="height: 135px;"></div>
                        <table width="100%">
                            <tr class="nospace-border">
                                <td width="60%" style="padding-left: 55px;">Department of Science And Technology</td>
                                <td width="30%" style="padding-left: 65px;">'. $model->purchase_request_number.'</td>
                                <td width="10%">'.$model->purchase_request_date.'</td>
                            </tr>
                        </table>';
        $LeftFooterContent = '
<table style="width: 50%;" border="0" cellpadding="1">
                                <tbody>
                                <tr>
                                <td><h6>'.$model->purchase_request_purpose.'</h6></td>
                                <td>&nbsp;</td>
                                </tr>
                                <tr>
                                <td><h6>Project Reference No. : '.$model->purchase_request_referrence_no.'</h6>
                                </td>
                                <td>&nbsp;</td>
                                </tr>
                                <tr>
                                <td><h6>Project Name : '.$model->purchase_request_project_name .'</h6></td>
                                <td>&nbsp;</td>
                                </tr>
                                <tr>
                                <td><h6>Project Location : '.$model->purchase_request_location_project.'</h6></td>
                                <td>&nbsp;</td>
                                </tr>
                                </tbody>
                                </table>';
        $s = "";
        $x = 0;
        while ($x<5) {
            $x++;
            $s = $s.'<tr class="nospace-border">
                      <td width="50%" style="text-align: right;padding-left: 50px;"></td>
                      <td width="50%" style="text-align: right;padding-right: 100px;"></td>
                     </tr>';
            }
        $LeftFooterContent =
            $LeftFooterContent.'<table width="100%">
                                    '.$s.'
                                    <tr class="nospace-border">
                                        <td width="50%" style="text-align: center;padding-left: 180px;">'.$requested_by.'<br/>'.$requested_by_position.'</td>
                                        <td width="50%" style="text-align: center;padding-left: 75px;">'.$approved_by.'<br/>'.$approved_by_position.'</td>
                                    </tr>
                                    <tr><td></td><td></td></tr>
                                    <tr><td></td><td></td></tr>
                                    <tr><td></td><td></td></tr> 
                                    <tr><td></td><td></td></tr>
                                    <tr><td></td><td></td></tr>
                                    <tr><td></td><td></td></tr> 
                                    <tr><td></td><td></td></tr>
                                    <tr><td></td><td></td></tr>
                                    <tr><td></td><td></td></tr> 
                                    <tr><td></td><td></td></tr>
                                    <tr><td></td><td></td></tr>
                                    <tr><td></td><td></td></tr>                                                                                                                                               
                                    <tr style="text-align: right;">
                                         <td>'.date("F j, Y").'</td>
                                         <td style="text-align: right;">Page {PAGENO} of {nbpg}</td>
                                    </tr>    
                                  </table>';

            $pdf->options = [
                'title' => 'Report Title',
                'defaultheaderline' => 0,
                'defaultfooterline' => 0,
                'subject'=> 'Report Subject'];
            $pdf->methods = [
                'SetHeader'=>[$headers],
                'SetFooter'=>[$LeftFooterContent],
            ];

            return $pdf->render();
    }

    /**
     * @return string
     * @throws \Throwable
     * @throws \yii\db\Exception
     */
    public function actionCreate()
    {
        $prequest = new Purchaserequest();
        $preqdetails = new Purchaserequestdetails();
        $session = Yii::$app->session;
        if ($prequest->load(Yii::$app->request->post()) || $preqdetails->load(Yii::$app->request->post()) ) {
            //*************Saving Record Here
            if ($prequest->validate() && $preqdetails->validate() ) {
                $connection =  Yii::$app->db;
                $transaction = $connection->beginTransaction();
                $lineitembudget = $prequest->lineitembudgetlist;
                $arr = json_decode($lineitembudget,true);
                try {
                    // all inputs are valid
                    $prnumber = $this->GeneratePRNumber();
                    $prequest->purchase_request_number = $prnumber; //'PR-13-01-0028';
                    $prequest->save();
                    $data=array();
                    foreach ($arr as $budgets) {
                        $unit = $budgets["Unit"];
                        $itemdescription = Html::encode($budgets["Item Description"]);
                        $quantity = $budgets["Quantity"];
                        $unitcost = $budgets["Unit Cost"];
                        $unit_type = $budgets["Unit"];
                        $totalCost = $budgets["Total Cost"];
                        $data[] =  [$prequest->purchase_request_id,$itemdescription,$quantity,$unitcost,$unit_type];
                    }
                    $connection->createCommand()->batchInsert
                    ('fais-procurement.tbl_purchase_request_details', ['purchase_request_id', 'purchase_request_details_item_description', 'purchase_request_details_quantity','purchase_request_details_price','unit_id'],$data)
                     ->execute();
                    $transaction->commit();
                    $session->set('savepopup',"executed");
                    return $this->redirect('index');
                } catch (\Exception $e) {
                    $transaction->rollBack();
                    $session->set('errorpopup',"executed");
                    throw $e;
                } catch (\Throwable $e) {
                    $transaction->rollBack();
                    $session->set('errorpopup',"executed");
                    throw $e;
                }
            } else {
                // validation failed: $errors is an array containing error messages
                $errors = $prequest->errors;
            }
        }else{
            return $this->renderAjax('_modal', [
                'model' => $prequest,
            ]) ;
        }
    }

    /**
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionUpdate()
    {
        $model = new Purchaserequest();
        $session = Yii::$app->session;
        $request = Yii::$app->request;

        if($request->get('id') && $request->get('view')) {
            $id = $request->get('id');
            $model = $this->findModel($id);
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                $connection =  Yii::$app->db;
                $lineitembudget = $model->lineitembudgetlist;
                $data=array();
                $arr = json_decode($lineitembudget,true);
                foreach ($arr as $budgets) {
                    $details = $budgets["Detail#"];
                    $unit = $budgets["Unit"];
                    $itemdescription = Html::encode($budgets["Item Description"]);
                    $quantity = $budgets["Quantity"];
                    $unitcost = $budgets["Unit Cost"];
                    $unit_type = $budgets["Unit"];
                    $totalCost = $budgets["Total Cost"];
                    if ($details=="-1") {
                        $data[] =  [$model->purchase_request_id,$itemdescription,$quantity,$unitcost,$unit_type];
                    }
                }
                $connection->createCommand()->batchInsert
                ('fais-procurement.tbl_purchase_request_details', ['purchase_request_id', 'purchase_request_details_item_description', 'purchase_request_details_quantity','purchase_request_details_price','unit_id'],$data)
                    ->execute();
                $session->set('updatepopup', "executed");
                //return $this->redirect(['index']);
                $this->redirect('index');
            } else {
                return $this->renderAjax('_modal', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     *
     */

    public function actionCheckprdetails() {
        $pr = Yii::$app->request;
        $pr_no = $pr->get('pno');
        $con = Yii::$app->procurementdb;
        $sql = "SELECT * FROM `fais-procurement`.`tbl_purchase_request_details` INNER JOIN `tbl_purchase_request` ON `tbl_purchase_request`.`purchase_request_id` = `tbl_purchase_request_details`.`purchase_request_id`
        WHERE `tbl_purchase_request`.`purchase_request_number` = '".$pr_no."'";
        $prdetails = $con->createCommand($sql)->queryAll();
        $data=array();
        $x = 0;
        foreach ($prdetails as $pr) {
            $x++;
            $data[] = ['purchase_request_details_id' => $pr["purchase_request_details_id"],
                'purchase_request_id' => $pr["purchase_request_id"],
                'unit_id' => $pr["unit_id"],
                'purchase_request_details_item_description' => $pr["purchase_request_details_item_description"],
                'purchase_request_details_quantity' => $pr["purchase_request_details_quantity"],
                'purchase_request_details_price' => $pr["purchase_request_details_price"]
            ];
        }
        return json_encode($data);
    }

    /**
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        $session = Yii::$app->session;
        $session->set('deletepopup',"executed");
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    /**
     *
     */

    public function actionDeletedetails()
    {
        $pr = Yii::$app->request;
        $session = Yii::$app->session;
        $pr_no = $pr->get('idno');
        $this->findModelDetails($pr_no)->delete();
        return 'success';
    }

    /**
     * Finds the Purchaserequest model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Purchaserequest the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Purchaserequest::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     *
     */

    protected function findModelDetails($id)
    {
        if (($model = Purchaserequestdetails::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * @return string
     * @throws \yii\db\Exception
     */

    public function GeneratePRNumber() {
        $characters = "PR";
        $yr = date('y');
        $mt = date('m');
        $con =  Yii::$app->db;
        $command = $con->createCommand("SELECT MAX(SUBSTR(`tbl_purchase_request`.`purchase_request_number`,10)) + 1 AS NextNumber FROM `fais-procurement`.`tbl_purchase_request`
WHERE MONTH(`tbl_purchase_request`.`purchase_request_date`) =" . $mt . " AND YEAR(`tbl_purchase_request`.`purchase_request_date`) = ". $yr);
        $nextValue = $command->queryAll();
        foreach ($nextValue as $bbb) {
            $a = $bbb['NextNumber'];
        }
        $nextValue = $a;
        $documentcode = $characters."-".$yr."-".$mt."-";
        $documentcode=$documentcode.str_pad($nextValue, 4, '0', STR_PAD_LEFT);
        return $documentcode;
    }


    function getprDetails($id)
    {
        //Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $con = Yii::$app->procurementdb;
        $sql = "SELECT *,
`fnGetAssignatoryName`(`tbl_purchase_request`.`purchase_request_requestedby_id`) AS requested_by,
`fnGetAssignatoryPosition`(`tbl_purchase_request`.`purchase_request_requestedby_id`) AS requested_by_position,
`fnGetAssignatoryName`(`tbl_purchase_request`.`purchase_request_approvedby_id`) AS approved_by,
`fnGetAssignatoryPosition`(`tbl_purchase_request`.`purchase_request_approvedby_id`) AS approved_by_position
FROM `tbl_purchase_request_details` 
INNER JOIN `tbl_purchase_request` 
ON `tbl_purchase_request`.`purchase_request_id` = `tbl_purchase_request_details`.`purchase_request_id`
INNER JOIN `fais`.`tbl_unit_type`
ON `tbl_unit_type`.`unit_type_id` = `tbl_purchase_request_details`.`unit_id`
WHERE `tbl_purchase_request_details`.`purchase_request_id`=".$id;
        $porequest = $con->createCommand($sql)->queryAll();
        return $porequest;
    }
    
    public function actionImport()
    {
        $pr_objPHPExcel = \PHPExcel_IOFactory::load(Yii::getAlias('@data').'/tblPRID.xlsx');
        $pr_sheetData = $pr_objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
        
        $prDetails_objPHPExcel = \PHPExcel_IOFactory::load(Yii::getAlias('@data').'/tblPurchaseRequest.xlsx');
        $prDetails_sheetData = $prDetails_objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
        
        $purchase_requests = $this->buildPRArray($pr_sheetData);
        $purchase_request_details = $this->buildPRDetailsArray($prDetails_sheetData);
        $new_PRs = $this->mergeData($purchase_requests, $purchase_request_details);
        
        return $this->render('import', [
            'purchase_requests' => $purchase_requests,
            'purchase_request_details' => $purchase_request_details,
            'prDetails_sheetData' => $prDetails_sheetData,
            'new_PRs' => $new_PRs,
        ]);
    }
    
    function mergeData($purchase_requests, $purchase_request_details)
    {
        $new_PRs = [];
        /*foreach($purchase_requests as $pr){
            $key = array_search($pr['purchase_request_number'], array_column($purchase_request_details, 'purchase_request_number'));
            array_push($pr['pr_details'], $purchase_request_details[$key]);
            array_push($new_PRs, $pr);
        }*/
        
        foreach($purchase_request_details as $pr_details){
            $key = null;
            $key = array_search($pr_details['purchase_request_number'], array_column($purchase_requests, 'purchase_request_number'));
            array_push($new_PRs, $purchase_requests[$key]);
            array_push($new_PRs[$key]['pr_details'], $pr_details);
        }
        return $new_PRs;
    }
    
    function buildPRArray($sheetData)
    {
        $purchase_requests = [];
        
        foreach($sheetData as $pr){
            $userDetails = $this->getUserDetails($pr['P'], $pr['I']);
            $date_array = explode('-', $pr['C']);
            
            
            if(isset($date_array[1])){
                $pr_date = date('Y-m-d', strtotime('20'.$date_array[2].'-'.$date_array[0].'-'.$date_array[1]));
                if(date('Y', strtotime($pr_date)) == 2019){
                    $pr_array = [
                      'purchase_request_id' => '',
                      'purchase_request_number' => $pr['B'], //PR_No
                      'purchase_request_sai_number' => $pr_date,
                      'division_id' => $userDetails['division_id'], //Department
                      'section_id' => $userDetails['section_id'], //Section
                      'purchase_request_date' => $pr_date, //PR_Date
                      'purchase_request_saidate' => '',
                      'purchase_request_purpose' => $pr['H'], //PR_Purpose
                      'purchase_request_referrence_no' => $pr['M'], //ProjRef_No
                      'purchase_request_project_name' => $pr['N'], //ProjName
                      'purchase_request_location_project' => $pr['O'], //
                      'purchase_request_requestedby_id' => $pr['D'], //PR_REQ
                      'purchase_request_approvedby_id' => $pr['F'], //PR_APPROV
                      'user_id' => $userDetails['user_id'], //User
                      'pr_details' => []
                    ];

                    array_push($purchase_requests, $pr_array);
                }
            }
            
        }
        
        return $purchase_requests;
    }
    
    function buildPRDetailsArray($sheetData)
    {
        $purchase_request_details = [];
        
        foreach($sheetData as $pr_details){
            /**(
                [A] => PR_No
                [B] => Item_No
                [C] => Unit
                [D] => Item_Description
                [E] => Quantity
                [F] => UnitCost
                [G] => TotalCost
            )**/
            $pr_details_array = [
              'purchase_request_details_id' => '',
              'purchase_request_id' => $pr_details['A'],
              'purchase_request_number' => $pr_details['A'],
              'purchase_request_details_unit' => $pr_details['C'],
              'unit_id' => $pr_details['C'],
              'purchase_request_details_item_description' => $pr_details['D'],
              'purchase_request_details_quantity' => $pr_details['E'],
              'purchase_request_details_price' => $pr_details['F'],
              'purchase_request_details_status' => 0
            ];
            
            array_push($purchase_request_details, $pr_details_array);
        }
        
        return $purchase_request_details;
    }
    
    function getUserDetails($user, $division)
    {
        $userDetails = User::find()
        ->where(['LIKE', 'username', substr($user, 0, 3)])
        ->one();
        
        $details = [
            'user_id' => $userDetails['user_id'],
            'username' => $userDetails['username'],
            'division_id' => '',
            'section_id' => ''
        ];
        
        return $details;
    }

}
