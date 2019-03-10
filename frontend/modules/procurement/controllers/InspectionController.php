<?php

namespace frontend\modules\procurement\controllers;
use common\models\procurement\Purchaserequest;
use common\models\procurement\PurchaserequestSearch;
use yii\data\ArrayDataProvider;

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
		         `tbl_purchase_order`.`purchase_order_id`
                 FROM `fais-procurement`.`tbl_purchase_order`
                 INNER JOIN `fais-procurement`.`tbl_purchase_order_details`
                 ON `tbl_purchase_order_details`.`purchase_order_id` = `tbl_purchase_order`.`purchase_order_id`
                 INNER JOIN `fais-procurement`.`tbl_bids_details`
                 ON `tbl_bids_details`.`bids_details_id` = `tbl_purchase_order_details`.`bids_details_id`
                 INNER JOIN `fais-procurement`.`tbl_bids` 
                 ON `tbl_bids`.`bids_id` = `tbl_bids_details`.`bids_id`";
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

}
