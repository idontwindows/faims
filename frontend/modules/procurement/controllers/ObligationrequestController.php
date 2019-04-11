<?php

namespace frontend\modules\procurement\controllers;

use Yii;
use common\models\procurement\Obligationrequest;
use common\models\procurement\ObligationrequestSearch;
use common\modules\pdfprint;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use kartik\mpdf\Pdf;

/**
 * ObligationrequestController implements the CRUD actions for Obligationrequest model.
 */
class ObligationrequestController extends Controller
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
     * Lists all Obligationrequest models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ObligationrequestSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Obligationrequest model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->renderAjax('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * @return string
     * @throws \yii\db\Exception
     */

    public function GenerateOSNumber($ostype) {
        if ($ostype=="PS") {
            $characters = "OS-100";
        }elseif ($ostype=="MOOE1") {
            $characters = "OS-200";
        }else{
            $characters = "OS-300";
        }
        $qry = "SELECT COUNT(`tbl_obligationrequest`.`os_no`) + 1 AS NextNumber  FROM `fais-procurement`.`tbl_obligationrequest` WHERE LEFT(`tbl_obligationrequest`.`os_no`,6) = '".$characters."'";
        $yr = date('y');
        $mt = date('m');
        $con =  Yii::$app->db;
        $command = $con->createCommand($qry);
        $nextValue = $command->queryAll();
        foreach ($nextValue as $bbb) {
            $a = $bbb['NextNumber'];
        }
        $nextValue = $a;
        $documentcode = $characters."-".$yr."-".$mt."-";
        $documentcode=$documentcode.str_pad($nextValue, 4, '0', STR_PAD_LEFT);
        return $documentcode;
    }

    /**
     *
     */

    public function actionReportob($id) {
        $request = Yii::$app->request;
        $id = $request->get('id');
        $model = $this->findModel($id);
        $obassig = $this->getobAssignatory($model->os_no);//  .date("F j, Y")
        $content = $this->renderPartial('_report', ['model'=> $model,'assig' => $obassig]);
        $pdf = new Pdf();
        $pdf->format = pdf::FORMAT_A4;
        $pdf->orientation = Pdf::ORIENT_PORTRAIT;
        $pdf->destination =  $pdf::DEST_BROWSER;
        $pdf->content  = $content;
        $pdf->cssFile = '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css';
        $pdf->cssInline = '.kv-heading-1{font-size:18px}.nospace-border{border:0px;}.no-padding{ padding:0px;}.print-container{font-size:11px;font-family:Arial,Helvetica Neue,Helvetica,sans-serif; }';
        $LeftFooterContent = '<div style="text-align: left;font-weight: lighter">'.$model->os_no.'</div>';
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

    function getobAssignatory($id)
    {
        //Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $con = Yii::$app->procurementdb;
        $sql = "SELECT `fnGetAssignatoryName`(`tbl_obligationrequest`.`requested_by`) AS RequestedBy , 
       `fnGetAssignatoryPosition`(`tbl_obligationrequest`.`requested_by`) AS RequestedPosition,
       `fnGetAssignatoryName`(`tbl_obligationrequest`.`funds_available`) AS FundsAvailable,
       `fnGetAssignatoryPosition`(`tbl_obligationrequest`.`funds_available`) AS FundsAvailablePosition
FROM `tbl_obligationrequest`
WHERE `tbl_obligationrequest`.`os_no` = '".$id."';";
        $porequest = $con->createCommand($sql)->queryAll();
        return $porequest;
    }



    /**
     *
     */

    function getobDetails($id)
    {
        //Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $con = Yii::$app->procurementdb;
        $sql = "SELECT * FROM `tbl_obligationrequest` WHERE `purchase_request_id`=".$id." ORDER BY obligation_request_id DESC";
        $porequest = $con->createCommand($sql)->queryAll();
        return $porequest;
    }

    /**
     * Creates a new Obligationrequest model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $ostype_data = [
            "PS" => "Personal Services",
            "MOOE1" => "Maintenance & Other Operation Expenses",
            "CO" => "Capital Outlay",
        ];
        $requesting = Yii::$app->request;
        $obrequest = new Obligationrequest();
        $con =  Yii::$app->db;
        $command_employee = $con->createCommand("SELECT `tbl_profile`.`user_id`,CONCAT(`tbl_profile`.`lastname`,', ', `tbl_profile`.`firstname` ,' ', `tbl_profile`.`middleinitial`, ' - ' , `tbl_profile`.`designation`) AS employeename
        FROM `tbl_profile`");
        $employees = $command_employee->queryAll();
        $command_po = $con->createCommand("SELECT `tbl_purchase_order`.`purchase_order_number` FROM `fais-procurement`.`tbl_purchase_order`");
        $ponum = $command_po->queryAll();
        $listEmployee = ArrayHelper::map($employees, 'user_id', 'employeename');
        $listPono = ArrayHelper::map($ponum, 'purchase_order_number', 'purchase_order_number');

        if ($obrequest->load(Yii::$app->request->post())) {
            if ($obrequest->validate()) {
                $osnumber = $this->GenerateOSNumber($obrequest->os_type);
                $obrequest->os_no = $osnumber;
                $obrequest->save();
                if(isset($_POST['btnSavePrint'])) {
                   return $this->redirect('reportob?id='.$obrequest->os_no);
                }else{
                   return $this->redirect('index');
                }
            } else {
                // validation failed: $errors is an array containing error messages
                $errors = $obrequest->errors;
                return $errors;
            }
        } else {
            if (Yii::$app->request->isAjax) {
                return $this->renderAjax('create', [
                    'model' => $obrequest,
                    'listEmployee'=>$listEmployee,
                    'listPono'=>$listPono,
                    'ostype_data'=>$ostype_data,
                ]);
            }else{
                return $this->render('create', [
                    'model' => $obrequest,
                    'listEmployee'=>$listEmployee,
                    'listPono'=>$listPono,
                    'ostype_data'=>$ostype_data,
                ]);
            }
        }
    }

    /**
     * @return string
     */

    public function actionCheckimportid()
    {
        $request = Yii::$app->request;
        $po_num = $request->post('po_num');
        $con = Yii::$app->procurementdb;
        $sql = "SELECT `tbl_purchase_order`.`purchase_order_number` ,CONCAT('TO PAYMENT of items to be delivered to DOST IX per P.O. No. ',`tbl_purchase_order`.`purchase_order_number`,
        ' dated ' , `tbl_purchase_order`.`purchase_order_date`) AS Particulars ";
        $sql = $sql.", SUM(`tbl_bids_details`.`bids_quantity` * `tbl_bids_details`.`bids_price`) AS Amount,
	    `tbl_purchase_order`.`purchase_order_date`
	    FROM `tbl_purchase_order` INNER JOIN `tbl_purchase_order_details`
	    ON `tbl_purchase_order_details`.`purchase_order_id` = `tbl_purchase_order`.`purchase_order_id`
	    INNER JOIN `tbl_bids_details` ON 
	    `tbl_bids_details`.`bids_details_id` = `tbl_purchase_order_details`.`bids_details_id`
	    WHERE `tbl_purchase_order`.`purchase_order_number` = '".$po_num."';";
        $checkxml = $con->createCommand($sql)->queryAll();
        return json_encode($checkxml);
    }

    /**
     * Updates an existing Obligationrequest model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate()
    {
        $model = new Obligationrequest();
        $ostype_data = [
            "PS" => "Personal Services",
            "MOOE1" => "Maintenance & Other Operation Expenses",
            "CO" => "Capital Outlay",
        ];
        $session = Yii::$app->session;
        $request = Yii::$app->request;
        if($request->get('id') && $request->get('view')) {
            $id = $request->get('id');
            $model = $this->findModel($id);
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                if(isset($_POST['btnUpdatePrint'])) {
                    return $this->redirect('reportob?id='.$id);
                }else{
                    return $this->redirect('index');
                }
            } else {
                $con =  Yii::$app->db;
                $command_employee = $con->createCommand("SELECT `tbl_profile`.`user_id`,CONCAT(`tbl_profile`.`lastname`,', ', `tbl_profile`.`firstname` ,' ', `tbl_profile`.`middleinitial`, ' - ' , `tbl_profile`.`designation`) AS employeename
                FROM `tbl_profile`");
                $employees = $command_employee->queryAll();
                $command_po = $con->createCommand("SELECT `tbl_purchase_order`.`purchase_order_number` FROM `fais-procurement`.`tbl_purchase_order`");
                $ponum = $command_po->queryAll();
                $listEmployee = ArrayHelper::map($employees, 'user_id', 'employeename');
                $listPono = ArrayHelper::map($ponum, 'purchase_order_number', 'purchase_order_number');
                //if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->renderAjax('_form', [
                    'model' => $model,
                    'ostype_data'=>$ostype_data,
                    'listEmployee'=>$listEmployee,
                    'listPono'=>$listPono,
                ]);
            }
        }


    }

    /**
     * Deletes an existing Obligationrequest model.
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
     * Finds the Obligationrequest model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Obligationrequest the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Obligationrequest::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
