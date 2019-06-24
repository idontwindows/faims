<?php

namespace frontend\modules\procurement\controllers;
use common\models\procurement\Purchaseorder;
use common\models\procurement\Purchaserequest;
use common\models\procurement\PurchaserequestSearch;
use yii\data\ArrayDataProvider;
use kartik\mpdf\Pdf;

//use yii\web\NotFoundHttpException;
use Yii;
//$model = new Purchaseorder();

class PurchaseorderController extends \yii\web\Controller
{

    /***
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new PurchaserequestSearch();
        $model = new Purchaseorder();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $data = $this->getPOList();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model'=>$model,
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


    /*  public  function actionPurchaseOrder() {

   /*   Modal::begin([
             'header' => '<h2>Hello world</h2>',
             'toggleButton' => ['label' => 'click me'],
         ]);
         echo 'Say hello...';
         Modal::end();

     }
     */

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
		 `tbl_purchase_request`.`purchase_request_date`
                 FROM `fais-procurement`.`tbl_purchase_order`
                 INNER JOIN `fais-procurement`.`tbl_purchase_order_details`
                 ON `tbl_purchase_order_details`.`purchase_order_id` = `tbl_purchase_order`.`purchase_order_id`
                 INNER JOIN `fais-procurement`.`tbl_bids_details`
                 ON `tbl_bids_details`.`bids_details_id` = `tbl_purchase_order_details`.`bids_details_id`
                 INNER JOIN `fais-procurement`.`tbl_bids` 
                 ON `tbl_bids`.`bids_id` = `tbl_bids_details`.`bids_id`
                 INNER JOIN `tbl_purchase_request`
                 ON `tbl_purchase_request`.`purchase_request_id` = `tbl_bids_details`.`purchase_request_id`
                 ORDER BY `tbl_purchase_order`.`purchase_order_number` DESC";
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
                 'purchase_order_id' => $pr["purchase_order_id"]
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
                 'purchase_order_id' => ''
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
		 `tbl_purchase_request`.`purchase_request_date`
                 FROM `fais-procurement`.`tbl_purchase_order`
                 INNER JOIN `fais-procurement`.`tbl_purchase_order_details`
                 ON `tbl_purchase_order_details`.`purchase_order_id` = `tbl_purchase_order`.`purchase_order_id`
                 INNER JOIN `fais-procurement`.`tbl_bids_details`
                 ON `tbl_bids_details`.`bids_details_id` = `tbl_purchase_order_details`.`bids_details_id`
                 INNER JOIN `fais-procurement`.`tbl_bids` 
                 ON `tbl_bids`.`bids_id` = `tbl_bids_details`.`bids_id`
                 INNER JOIN `tbl_purchase_request`
                 ON `tbl_purchase_request`.`purchase_request_id` = `tbl_bids_details`.`purchase_request_id`
                 WHERE `tbl_purchase_order`.`purchase_order_number` = '".$id."'";
         $porequest = $con->createCommand($sql)->queryAll();
         return $porequest;
     }


     public function actionReportpo($id) {
         $request = Yii::$app->request;
         $id = $request->get('id');
         $model = $this->findModelDetails($id);
         $prdetails = $this->getprDetails($id);
         $assig = $this->getassig();
         $content = $this->renderPartial('_report', ['prdetails'=> $prdetails,'model'=>$model]);
         $pdf = new Pdf();
         $pdf->mode = pdf::MODE_UTF8;
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

         foreach ($assig as $sg) {
             $assig1 =  $sg["Assig1"];
             $assig2 =  $sg["Assig2"];
             $Assig1Position =  $sg["Assig1Position"];
             $Assig2Position =  $sg["Assig2Position"];
         }
         $pdf->marginTop = 45;
         //$pdf->marginHeader = 40;
         $pdf->marginBottom =50;

         $headers= '<div style="height: 150px"></div>
                    <table border="0" width="100%">
                   
                    
                        <tr style="text-align: left;">
                            <td style="padding-left: 50px;">'.$supplier.'</td>
                            <td style="text-align: right;">'.$ponum.'</td>
                        </tr>
                        <tr style="text-align: right;">
                            <td style="padding-left: 50px;">Zamboanga City</td>
                            <td style="text-align: right;">'.$pdate.'</td>
                        </tr>
                        <tr style="text-align: right;">
                            <td></td>
                            <td style="text-align: right;"></td>
                        </tr> 
                            <tr style="text-align: right;">
                            <td></td>
                            <td style="text-align: right;"></td>
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
                            <td style="padding-left: 85px;">Department of Science and Technology</td>
                            <td style="text-align: right;"></td>
                        </tr>                                          
                    </table>
                    ';
         $summary = 0;
         $totalcost = 0;
         foreach ($prdetails as $pr) {
             $quantity = $pr["bids_quantity"];
             $price = $pr["bids_price"];
             $totalcost =  $quantity * $price;
             $summary = $summary + $totalcost;
         }
         $footerss= '<div style="height: 10px"></div>

                    <table border="0" width="100%">
 
                     <tr class="nospace-border">
                     <td width="85%" colspan="4">&nbsp;</td>
                     <td width="15%" style="padding-left: 25px;">&nbsp;</td>
                     </tr>
                      <tr class="nospace-border">
                     <td width="85%" colspan="4">&nbsp;</td>
                     <td width="15%" style="padding-left: 25px;">&nbsp;</td>
                     </tr>
                      <tr class="nospace-border">
                     <td width="85%" colspan="4">&nbsp;</td>
                     <td width="15%" style="padding-left: 25px;">&nbsp;</td>
                     </tr>      
                             <tr class="nospace-border">
                     <td width="85%" colspan="4">&nbsp;</td>
                     <td width="15%" style="padding-left: 25px;">&nbsp;</td>
                     </tr>      
                        <tr style="text-align: left;">
                            <td style="padding-left: 80px;">'.$supplier.'</td>
                            <td style="text-align: center;">'.$assig2.'<br>'.$Assig2Position.'</td>
                       </tr>
                       <tr><td></td><td></td></tr>
                       <tr><td></td><td></td></tr>
                       <tr><td></td><td></td></tr>
                       <tr><td></td><td></td></tr>
                       <tr><td></td><td></td></tr>
                       <tr><td></td><td></td></tr>
                       <tr><td></td><td></td></tr>
                       <tr><td></td><td></td></tr>
                       
                        <tr style="text-align: right;padding-left: 50px;">
                            <td style="text-align: center;">'.$assig1.'<br>'.$Assig1Position.'</td>
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
                    
                        <tr style="text-align: right;">
                            <td>'.date("F j, Y").'</td>
                            <td style="text-align: right;">Page {PAGENO} of {nbpg}</td>
                        </tr>              
                    </table>
                    ';
         $pdf->options = [
             'title' => 'Report Title',
             'defaultheaderline' => 0,
             'defaultfooterline' => 0,
             'subject'=> 'Report Subject'];
         $pdf->methods = [
             'SetHeader'=>[$headers],
             'SetFooter'=>[$footerss],
         ];

         return $pdf->render();
     }


    function getassig()
    {
        $con = Yii::$app->db;
        $sql = "	SELECT `fais-procurement`.`fnGetAssignatoryName`(`tbl_assignatory`.`assignatory_1`) AS Assig1 , 
	       `fais-procurement`.`fnGetAssignatoryPosition`(`tbl_assignatory`.`assignatory_1`) AS Assig1Position,
	       `fais-procurement`.`fnGetAssignatoryName`(`tbl_assignatory`.`assignatory_2`) AS Assig2 , 
	       `fais-procurement`.`fnGetAssignatoryPosition`(`tbl_assignatory`.`assignatory_2`) AS Assig2Position
	       	FROM `tbl_assignatory`
	WHERE `tbl_assignatory`.`assignatory_id` = 7";
        $pordetails = $con->createCommand($sql)->queryAll();
        return $pordetails;
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
