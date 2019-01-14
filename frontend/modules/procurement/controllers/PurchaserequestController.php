<?php

namespace frontend\modules\procurement\controllers;
use common\models\procurement\Purchaserequestdetails;
use Yii;
use common\models\procurement\Purchaserequest;
use common\models\procurement\PurchaserequestSearch;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use kartik\mpdf\Pdf;

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
            return $this->renderAjax('_view', [
                'model' => $model,
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
            $pdf->cssInline = '.kv-heading-1{font-size:18px}.nospace-border{border:0px;}.no-padding{ padding:0px;}.print-container{font-size:11px;font-family:Arial,Helvetica Neue,Helvetica,sans-serif; }';
            $LeftFooterContent = '<div style="text-align: left;font-weight: lighter">Monday, April 30, 2018</div>';
            $RightFooterContent = '<div style="text-align: right;padding-top:-50px;">Page {PAGENO} of {nbpg}</div>';
            $oddEvenConfiguration =
                [
                    'L' => [ // L for Left part of the header
                        'content' => $LeftFooterContent,
                    ],
                    'C' => [ // C for Center part of the header
                        'content' => '',
                    ],
                    'R' => [
                        'content' => $RightFooterContent,
                    ],
                    'line' => 0, // That's the relevant parameter
                ];
            $headerFooterConfiguration = [
                'odd' => $oddEvenConfiguration,
                'even' => $oddEvenConfiguration
            ];
            $pdf->options = [
                'title' => 'Report Title',
                'subject'=> 'Report Subject'];
            $pdf->methods = [
                'SetHeader'=>[''],
                'SetFooter'=>[$headerFooterConfiguration],
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
                        $itemdescription = $budgets["Item Description"];
                        $quantity = $budgets["Quantity"];
                        $unitcost = $budgets["Unit Cost"];
                        $totalCost = $budgets["Total Cost"];
                        $data[] =  [$prequest->purchase_request_id,$itemdescription,$quantity,$unitcost];
                    }
                    $connection->createCommand()->batchInsert
                    ('fais-procurement.tbl_purchase_request_details', ['purchase_request_id', 'purchase_request_details_item_description', 'purchase_request_details_quantity','purchase_request_details_price'],$data)
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

    public function  actionTestajax() {
       // $request = Yii::$app->request;
        //$id = $request->get('id');
        //return $id;
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
        $command = $con->createCommand("SELECT COUNT(`tbl_purchase_request`.`purchase_request_id`) + 1 AS NextNumber FROM `fais-procurement`.`tbl_purchase_request`");
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
        $sql = "SELECT * FROM `tbl_purchase_request_details` WHERE `purchase_request_id`=".$id;
        $porequest = $con->createCommand($sql)->queryAll();
        return $porequest;
    }

}
