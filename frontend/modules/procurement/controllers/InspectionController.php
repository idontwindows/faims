<?php

namespace frontend\modules\procurement\controllers;
use common\models\procurement\Purchaseorderdetails;
use common\models\procurement\Purchaserequest;
use common\models\procurement\PurchaserequestSearch;
use common\models\procurement\Purchaseorder;
use yii\data\ArrayDataProvider;
use kartik\mpdf\Pdf;
use Yii;
class InspectionController extends \yii\web\Controller
{

    /***
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new PurchaserequestSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $data = $this->getPOList();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'mydata' => $data,
        ]);
    }



    /**
     * Displays a single PurchaseRequest model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
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


    public  function actionPurchaseOrder() {

        Modal::begin([
            'header' => '<h2>Hello world</h2>',
            'toggleButton' => ['label' => 'click me'],
        ]);
        echo 'Say hello...';
        Modal::end();

    }


    function getPOList()
    {
        $con = Yii::$app->procurementdb;
        $sql = "SELECT `tbl_purchase_order`.`purchase_order_number`  ,
                 `tbl_bids_details`.`bids_details_id`,
                 `fnGetSupplierName`(`tbl_bids`.`supplier_id`) AS supplier_name,
                 `tbl_bids_details`.`bids_item_description` , 
                 `tbl_bids_details`.`bids_quantity` , 
                 `tbl_bids_details`.`bids_unit` , 
                 `tbl_bids_details`.`bids_price`,
		 `tbl_purchase_order`.`purchase_order_id`,
		 `tbl_purchase_order_details`.`delivered`,
		 `tbl_purchase_order`.`purchase_order_date`,
		 `tbl_purchase_request`.`purchase_request_number`,
		 `tbl_purchase_request`.`purchase_request_date`,
		 `tbl_purchase_order_details`.`purchase_order_details_id`
                 FROM `fais-procurement`.`tbl_purchase_order`
                 INNER JOIN `fais-procurement`.`tbl_purchase_order_details`
                 ON `tbl_purchase_order_details`.`purchase_order_id` = `tbl_purchase_order`.`purchase_order_id`
                 INNER JOIN `fais-procurement`.`tbl_bids_details`
                 ON `tbl_bids_details`.`bids_details_id` = `tbl_purchase_order_details`.`bids_details_id`
                 INNER JOIN `fais-procurement`.`tbl_bids` 
                 ON `tbl_bids`.`bids_id` = `tbl_bids_details`.`bids_id`
                 INNER JOIN `tbl_purchase_request`
                 ON `tbl_purchase_request`.`purchase_request_id` = `tbl_bids_details`.`purchase_request_id`";
        $pordetails = $con->createCommand($sql)->queryAll();

        $x = 0;
        foreach ($pordetails as $pr) {
            $x++;
            $data[] = ['purchase_order_number' => $pr["purchase_order_number"],
                'bids_details_id' => $pr["bids_details_id"],
                'bids_unit' => $pr["bids_unit"],
                'supplier_name' => $pr["supplier_name"],
                'bids_item_description' => $pr["bids_item_description"],
                'bids_quantity' => $pr["bids_quantity"],
                'bids_price' => $pr["bids_price"],
                'purchase_order_id' => $pr["purchase_order_id"],
                'delivered' => $pr["delivered"],
                'purchase_order_details_id' => $pr["purchase_order_details_id"]
            ];
        }
        if ($x == 0) {
            $data[] = ['purchase_order_number' => '',
                'bids_details_id' => '',
                'bids_unit' => '',
                'supplier_name'=> '',
                'bids_item_description' => '',
                'bids_quantity' => '',
                'bids_price' => '',
                'purchase_order_id' => '',
                'delivered'=>'',
                'purchase_order_details_id' => $pr["purchase_order_details_id"]
            ];
        }

        $pordetails = $data; //$provider;

        return $pordetails;

    }


    function getprDetails($id)
    {
        //Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $con = Yii::$app->procurementdb;
        $sql = "SELECT `tbl_purchase_order`.`purchase_order_number`  ,
                 `tbl_bids_details`.`bids_details_id`,
                 `fnGetSupplierName`(`tbl_bids`.`supplier_id`) AS supplier_name,
                 `tbl_bids_details`.`bids_item_description` , 
                 `tbl_bids_details`.`bids_quantity` , 
                 `tbl_bids_details`.`bids_unit` , 
                 `tbl_bids_details`.`bids_price`,
		 `tbl_purchase_order`.`purchase_order_id`,
		 `tbl_purchase_order_details`.`delivered`,
		 `tbl_purchase_order`.`purchase_order_date`,
		 `tbl_purchase_request`.`purchase_request_number`,
		 `tbl_purchase_request`.`purchase_request_date`,
		`tbl_purchase_order_details`.`purchase_order_details_id`
                 FROM `fais-procurement`.`tbl_purchase_order`
                 INNER JOIN `fais-procurement`.`tbl_purchase_order_details`
                 ON `tbl_purchase_order_details`.`purchase_order_id` = `tbl_purchase_order`.`purchase_order_id`
                 INNER JOIN `fais-procurement`.`tbl_bids_details`
                 ON `tbl_bids_details`.`bids_details_id` = `tbl_purchase_order_details`.`bids_details_id`
                 INNER JOIN `fais-procurement`.`tbl_bids` 
                 ON `tbl_bids`.`bids_id` = `tbl_bids_details`.`bids_id`
                 INNER JOIN `tbl_purchase_request`
                 ON `tbl_purchase_request`.`purchase_request_id` = `tbl_bids_details`.`purchase_request_id`
                 WHERE `tbl_purchase_order`.`purchase_order_number` = '".$id."' and `tbl_purchase_order_details`.`delivered`=1";
        $porequest = $con->createCommand($sql)->queryAll();
        return $porequest;
    }


    public function actionCheckselected()
    {
        $session = Yii::$app->session;
        $request = Yii::$app->request->post();
        $id = $request["chkRow"];
        $bools = $request["chkStatus"];
        Purchaseorderdetails::updateAll(['delivered' => $bools], 'purchase_order_details_id = ' . $id);
        $checkStatus=$id;
        return $checkStatus;
    }

    public function actionReportpo($id) {
        $request = Yii::$app->request;
        $id = $request->get('id');
        $model = $this->findModelDetails($id);
        $prdetails = $this->getprDetails($id);
        $content = $this->renderPartial('_report', ['prdetails'=> $prdetails,'model'=>$model]);
        $pdf = new Pdf();
        $pdf->format = pdf::FORMAT_A4;
        $pdf->orientation = Pdf::ORIENT_PORTRAIT;
        $pdf->destination =  $pdf::DEST_BROWSER;
        $pdf->content  = $content;
        $pdf->cssFile = '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css';
        $pdf->cssInline = '.kv-heading-1{font-size:18px}.nospace-border{border:0px;}.no-padding{ padding:0px;}.print-container{font-size:11px;font-family:Arial,Helvetica Neue,Helvetica,sans-serif;}h6 {  }';
        $supplier='';
        $ponum='';
        $prno='';
        $pdate='';
        $prdate='';
        foreach ($prdetails as $pr) {
            $supplier = $pr["supplier_name"];
            $ponum = $pr["purchase_order_number"];
            $pdate = $pr["purchase_order_date"];
            $prno = $pr["purchase_request_number"];
            $prdate = $pr["purchase_request_date"];
        }
        $pdf->marginTop = 45;
        $pdf->marginBottom = 75;
        $pdf->marginFooter = 5;

        $headers= '<div style="height: 50px"></div>
                    <table border="0" width="100%">
                        <tr style="text-align: left;">
                            <td>'.$supplier.'</td>
                            <td style="text-align: right;">'.$ponum.'</td>
                        </tr>
                        <tr style="text-align: right;">
                            <td>Zamboanga City</td>
                            <td style="text-align: right;">'.$pdate.'</td>
                        </tr>
                        <tr style="text-align: right;">
                            <td></td>
                            <td style="text-align: right;"></td>
                        </tr> 
                        <tr style="text-align: right;">
                            <td></td>
                            <td style="text-align: right;">'.$prno.'</td>
                        </tr>    
                        <tr style="text-align: right;">
                            <td></td>
                            <td style="text-align: right;">'.$prdate.'</td>
                        </tr>        
                        <tr style="text-align: right;">
                            <td>Department of Science and Technology</td>
                            <td style="text-align: right;"></td>
                        </tr>                                          
                    </table>
                    ';
        $footerss= '<div style="height: 50px"></div>
                    <table border="0" width="100%">
                        <tr style="text-align: left;">
                            <td>'.$supplier.'</td>
                            <td style="text-align: right;">MARTIN A. WEE</td>
                        </tr>
                       <tr><td></td><td></td></tr>
                       <tr><td></td><td></td></tr>
                        <tr style="text-align: right;">
                            <td>ROBERT B. ABELLA</td>
                            <td style="text-align: right;"></td>
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
                    </table>
                    ';
        $LeftFooterContent = '<div style="text-align: left;">'.date("F j, Y").'</div>';
        $CenterFooterContent = '';
        $RightFooterContent = '<div style="text-align: right;">Page {PAGENO} of {nbpg}</div>';
        $oddEvenConfiguration =
            [
                'L' => [ // L for Left part of the header
                    'content' => $LeftFooterContent,
                    'font-size' => 7,
                    'footer-style-left' => 300,
                    'font-family' => 'Arial',
                    'color'=>'#000000'
                ],
                'C' => [ // C for Center part of the header
                    'content' => $CenterFooterContent,
                    'font-size' => 6,
                    'font-style' => 'B',
                    'font-family' => 'arial',
                    'color'=>'#000000',
                ],
                'R' => [
                    'content' => $RightFooterContent,
                    'font-size' => 6,
                    'font-style' => 'B',
                    'font-family' => 'arial',
                    'color'=>'#000000'
                ],
                'line' =>0, // That's the relevant parameter
            ];
        $headerFooterConfiguration = [
            'odd' => $oddEvenConfiguration,
            'even' => $oddEvenConfiguration
        ];
        $pdf->options = [
            'title' => 'Report Title',
            'defaultheaderline' => 0,
            'subject'=> 'Report Subject'];
        $pdf->methods = [
            'SetHeader'=>[$headers],
            'SetFooter'=>[$footerss],
        ];

        return $pdf->render();
    }


    /**
     * Finds the PurchaseRequest model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PurchaseRequest the loaded model
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

    protected function findModelDetails($id)
    {
        if (($model = Purchaseorder::findOne($id)) !== null) {
            return $model;
        } else {
            //return var_dump($model);
            //throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}