<?php

namespace frontend\modules\procurement\controllers;
use common\models\procurement\Bids;
use common\models\procurement\Bidsdetails;
use common\models\procurement\BidsdetailSearch;
use common\models\procurement\BidsSearch;
use common\models\procurement\Purchaseorder;
use common\models\procurement\Purchaserequest;
use common\models\procurement\Purchaserequestdetails;
use common\models\procurement\Purchaserequestsearchdetails;
use common\models\procurement\Supplier;
use kartik\grid\EditableColumnAction;
use yii\data\SqlDataProvider;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use Yii;
use yii\web\Controller;
use kartik\mpdf\Pdf;

class BidsController extends Controller
{
    /***
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new Purchaserequestsearchdetails();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actions()
    {
        return ArrayHelper::merge(parent::actions(), [

            'editPrice' => [                                       // identifier for your editable action
                'class' => EditableColumnAction::className(),     // action class name
                'modelClass' => Purchaserequestdetails::className(),                // the update model class
                'outputValue' => function ($model, $attribute, $key, $index) {
                    $fmt = Yii::$app->formatter;
                    $value = $model->$attribute;
                    // your attribute value
                    $mystatus = $model->purchase_request_details_status;
                    if ($mystatus==2) {
                        if ($attribute === 'purchase_request_details_price') {           // selective validation by attribute
                            return $fmt->asDecimal('0.00', 2);       // return formatted value if desired
                        }
                        return '';
                    }else{
                        $model->purchase_request_details_status = 1;
                        $model->save();
                        if ($attribute === 'purchase_request_details_price') {           // selective validation by attribute
                            return $fmt->asDecimal($value, 2);       // return formatted value if desired
                        }
                        return '';
                    }
                                              // empty is same as $value
                },
                'outputMessage' => function ($model, $attribute, $key, $index) {
                    return ''; // any custom error after model save
                },
            ]

            ,


            'editRemarks' => [                                       // identifier for your editable action
                'class' => EditableColumnAction::className(),     // action class name
                'modelClass' => Bidsdetails::className(),                // the update model class
                'outputValue' => function ($model, $attribute, $key, $index) {
                    $fmt = Yii::$app->formatter;
                    $value = $model->$attribute;
                    // your attribute value
                    $model->save();
                    if ($attribute === 'bids_remarks') {           // selective validation by attribute
                        return $fmt->asText($value);       // return formatted value if desired
                    }
                    return '';
                    // empty is same as $value
                },
                'outputMessage' => function ($model, $attribute, $key, $index) {
                    return ''; // any custom error after model save
                },
            ],


              'editDescription' => [                                       // identifier for your editable action
            'class' => EditableColumnAction::className(),     // action class name
            'modelClass' => Bidsdetails::className(),                // the update model class
            'outputValue' => function ($model, $attribute, $key, $index) {
                $fmt = Yii::$app->formatter;
                $value = $model->$attribute;
                // your attribute value

                //$model->save();
                if ($attribute === 'bids_item_description') {           // selective validation by attribute
                    return $fmt->asText($value);       // return formatted value if desired
                }
                return '';
                // empty is same as $value
            },
            'outputMessage' => function ($model, $attribute, $key, $index) {
                return ''; // any custom error after model save
            },
            ]

            ,


            'editPrice2' => [                                       // identifier for your editable action
                'class' => EditableColumnAction::className(),     // action class name
                'modelClass' => Bidsdetails::className(),                // the update model class
                'outputValue' => function ($model, $attribute, $key, $index) {
                    $fmt = Yii::$app->formatter;
                    $value = $model->$attribute;
                        //$model->purchase_request_details_status = 1;
                        $model->save();
                        if ($attribute === 'bids_price') {           // selective validation by attribute
                            return $fmt->asDecimal($value, 2);       // return formatted value if desired
                        }
                        return '';
                    // empty is same as $value
                },
                'outputMessage' => function ($model, $attribute, $key, $index) {
                    return ''; // any custom error after model save
                },
            ]



        ]);

    }




    /**
     * Displays a single PurchaseRequest model.
     * @param integer $id
     * @return mixed
     */


    public function actionCreatepo()
    {
        $request = Yii::$app->request;
        $bids = new Bids();
        $bidsdetails = new Bidsdetails();
        $prdetails = new Purchaserequestdetails();
        $supplierid = $request->post('supplierid');
        $array_rows = $request->post('array_rows');
        $data_table = $request->post('tabledata');
        $pID = $request->post('pID');
        $arr = json_decode($data_table, true);
        $data = array();
        $cont = "";
        $prdetailss = $this->getprDetails($pID);
        $b = "";
        $connection = Yii::$app->db;
        $procCon = Yii::$app->procurementdb;
        $transaction = $connection->beginTransaction();
        try {
            $bids->purchase_request_id = $pID;
            $bids->supplier_id = $supplierid;
            $bids->save();
            $data = array();
            foreach ($prdetailss as $pr) {
                $unit = "Units";
                $itemdescription = $pr["purchase_request_details_item_description"];
                $quantity = $pr["purchase_request_details_quantity"];
                $price = $pr["purchase_request_details_price"];
                $stats = $pr["purchase_request_details_status"];
                $requestID = $pr["purchase_request_id"];
                $prdetailID = $pr["purchase_request_details_id"];
                if ($stats == 1) {
                    $data[] = [$bids->bids_id, $unit, $itemdescription, $quantity, $price, $requestID, $prdetailID];
                }
            }
            $updateStatus = "UPDATE tbl_purchase_request_details SET purchase_request_details_status=0 , purchase_request_details_price=0 WHERE `purchase_request_id`=" . $pID ." AND purchase_request_details_status<>2";
            $procCon->createCommand($updateStatus)->query();
            $procCon->createCommand()->batchInsert
            ('tbl_bids_details', ['bids_id', 'bids_unit', 'bids_item_description', 'bids_quantity', 'bids_price', 'purchase_request_id', 'purchase_request_details_id'], $data)
                ->execute();
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        } catch (\Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }


    }

    public function GeneratePONumber()
    {
        $characters = "PO";
        $yr = date('y');
        $mt = date('m');
        $con = Yii::$app->db;
        $command = $con->createCommand("SELECT COUNT(`tbl_purchase_order`.`purchase_order_id`) + 1 AS NextNumber FROM `fais-procurement`.`tbl_purchase_order`");
        $nextValue = $command->queryAll();
        foreach ($nextValue as $bbb) {
            $a = $bbb['NextNumber'];
        }
        $nextValue = $a;
        $documentcode = $characters . "-" . $yr . "-" . $mt . "-";
        $documentcode = $documentcode . str_pad($nextValue, 4, '0', STR_PAD_LEFT);
        return $documentcode;
    }


    public function actionCreatepurchase()
    {
        $request = Yii::$app->request;
        $array_rows = $request->post('array_rows');
        $data_table = $request->post('tabledata');
        $arr2 = json_decode($array_rows, true);
        $arr = json_decode($data_table, true);
        $pOrder = new PurchaseOrder();
        $s = 0;
        $mstat="";
        $PoID = $this->GeneratePONumber();
        $data = $arr2;
        $sizess = count($data) - 1;
        $connection = Yii::$app->db;
        $procCon = Yii::$app->procurementdb;
        $transaction = $procCon->beginTransaction();
        try {
            $curdate = date('Y-m-d');
            $pOrder->purchase_order_number = $PoID;
            $pOrder->purchase_order_date = $curdate;
            $pOrder->save();
            do {
                $mdata = $data[$s];
                $biddetails = $this->getbidDetails2($mdata);
                foreach ($biddetails as $bid) {
                    $purchase_request_details_id = $bid["purchase_request_details_id"];
                    Purchaserequestdetails::updateAll(['purchase_request_details_status' => 2], 'purchase_request_details_id = ' . $purchase_request_details_id);
                    Bidsdetails::updateAll(['bids_details_status' => 4], 'purchase_request_details_id = ' . $purchase_request_details_id);
                    $stats = $this->checkbidDetailStatus($mdata);
                    foreach ($stats as $stt) {
                        $mstat = $stt["ifexist"];
                    }
                    if ($mstat==0) {
                        $procCon->createCommand()->insert
                        ('tbl_purchase_order_details', ['purchase_order_id' => $pOrder->purchase_order_id, 'bids_details_id' => $mdata])
                            ->execute();
                    }
                }
                Bidsdetails::updateAll(['bids_details_status' => 1], 'bids_details_id = ' . $mdata);
                    $s++;
            } while ($s <= $sizess);
            $transaction->commit();
            } catch (\Exception $e) {
                $transaction->rollBack();
                throw $e;
            } catch (\Throwable $e) {
                $transaction->rollBack();
                throw $e;
            }
        return $mstat;
    }

    public function actionCheckselected()
    {
        $session = Yii::$app->session;
        $request = Yii::$app->request->post();
        $id = $request["chkRow"];
        $old_bids_id = $session["temp_bids_id"];
        $checkboxStatus = $request["chkStatus"];
        $chkLotBidStatus = $request["chkBids"];
        $bidsdetail = new Bidsdetails();
        $bidsdetail = $this->findbidsdetail($id);
        $biddetails = $this->getpbidDetails($bidsdetail->purchase_request_details_id);
        $checkStatus = $this->actionCheckbidstatus($bidsdetail->purchase_request_id);
        $bID = $bidsdetail->bids_details_id;
        $bbID = $bidsdetail->bids_id;
        foreach ($biddetails as $bid) {
            $bids_details_status = $bid["bids_details_status"];
            $bids_ids = $bid["bids_id"];
            $purchase_request_details_id = $bid["purchase_request_details_id"];
            if ($chkLotBidStatus == 1) {
                if ($checkboxStatus == 1) {
                    Bidsdetails::updateAll(['bids_details_status' => 3], 'bids_details_id = ' . $bID);
                } else {
                    Bidsdetails::updateAll(['bids_details_status' => 0], ['and', ['purchase_request_details_id' => $purchase_request_details_id], ['<>', 'bids_details_status', 1], ['<>', 'bids_details_status', 4]]);
                }
                if ($checkStatus == 0) {
                    $session["temp_bids_id"] = $bbID;
                } else {
                    if ($old_bids_id <> $bbID) {
                        if ($bids_details_status <> 0 || $bids_details_status <> 1 || $bids_details_status <> 4) {
                            Bidsdetails::updateAll(['bids_details_status' => 0], ['and', ['bids_id' => $old_bids_id], ['<>', 'bids_details_status', 1], ['<>', 'bids_details_status', 4]]);
                            Bidsdetails::updateAll(['bids_details_status' => 0], ['and', ['bids_id' => $bbID], ['<>', 'bids_details_status', 1], ['<>', 'bids_details_status', 4]]);
                        }
                    } else {
                        $session["temp_bids_id"] = $bbID;
                    }
                    $session["temp_bids_id"] = $bbID;
                }
            } else {
                $bids_details_status = $bid["bids_details_status"];
                $bids_ids = $bid["bids_id"];
                $purchase_request_details_id = $bid["purchase_request_details_id"];
                if ($checkboxStatus == 1) {
                    Bidsdetails::updateAll(['bids_details_status' => 0], ['and', ['purchase_request_details_id' => $purchase_request_details_id], ['=', 'bids_details_status', 3]]);
                    Bidsdetails::updateAll(['bids_details_status' => 3], 'bids_details_id = ' . $bID);
                } else {
                    Bidsdetails::updateAll(['bids_details_status' => 0], ['and', ['purchase_request_details_id' => $purchase_request_details_id], ['<>', 'bids_details_status', 1], ['<>', 'bids_details_status', 4]]);
                }
            }
        }
        return $checkStatus;
    }

    public function actionChecksupplier()
    {
        $request = Yii::$app->request;
        $supplierid = $request->post('supplierid');
        $pID = $request->post('pID');
        $con = Yii::$app->procurementdb;
        $sql = "SELECT COUNT(`tbl_bids`.`bids_id`) AS ifExist FROM `tbl_bids` WHERE `tbl_bids`.`supplier_id`='" . $supplierid . "' AND `tbl_bids`.`purchase_request_id`='" . $pID . "'";
        $CheckIfExist = $con->createCommand($sql)->queryAll();
        foreach ($CheckIfExist as $Exist) {
            $x = $Exist["ifExist"];
        }
        return $sql ;

    }

    public function actionCheckbidstatus($purchase_id)
    {
        $con = Yii::$app->procurementdb;
        $sql = "SELECT COUNT(*) AS bidExists FROM `tbl_bids_details` WHERE bids_details_status=3 AND purchase_request_id=" . $purchase_id;
        $CheckIfExist = $con->createCommand($sql)->queryAll();
        foreach ($CheckIfExist as $Exist) {
            $x = $Exist["bidExists"];
        }
        return $x;
    }

    public function actionCancelbids()
    {
        $request = Yii::$app->request;
        $id = $request->get('id');
        foreach (Bids::find()->where('bids_id='.$id)->all() as $user) {
            $user->delete();
        }
        foreach (Bidsdetails::find()->where('bids_id='.$id)->all() as $user) {
            $user->delete();
        }
        $id = $request->get('íd');
        $pid = $request->get('pid');
        $model = $this->findModel($pid);
        $m = $this->findSupplier();
        $prdetails = $this->getprDetails($pid);
        $biddetails = $this->getbidDetails($pid);
        $ListPOprovider = $this->getpoDetails($pid);
        $searchModel = new Purchaserequestsearchdetails();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $searchModelBid = new BidsSearch();
        $bidsProvider = $searchModelBid->search(Yii::$app->request->queryParams);
        $dataProvider->query->where('purchase_request_id=' . $pid);


        return $this->renderAjax('_bids', [
            'model' => $model, 'prdetails' => $prdetails, 'biddetails' => $biddetails, 'searchModel' => $searchModel, 'dataProvider' => $dataProvider,
            'searchModelBid' => $searchModelBid, 'bidsProvider' => $bidsProvider, 'ListPOprovider' => $ListPOprovider , 'supp' => $m
        ]);

    }


    public function actionReport()
{
    $request = Yii::$app->request;
    $id = $request->post('id');
    $supplier = $request->post('cbosupplier');
    $address = $request->post('txtaddress');
    $sub = $request->post('txtsubmission');
    $employee = $request->post('cboemployees');
    $employee = explode("|",$employee);
    $sub = date_create($sub);
    $model = $this->findModel($id);
    $prdetails = $this->getprDetails($model->purchase_request_id);
    $content = $this->renderPartial('_report', ['prdetails' => $prdetails, 'model' => $model]);
    $pdf = new Pdf();
    $pdf->format = pdf::FORMAT_A4;
    $pdf->orientation = Pdf::ORIENT_PORTRAIT;
    $pdf->destination = $pdf::DEST_BROWSER;

    $pdf->content = $content;
    $pdf->cssFile = '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css';
    $pdf->cssInline = '.kv-heading-1{font-size:18px}.nospace-border{border:0px;}.no-padding{ padding:0px;}.print-container{font-size:11px;font-family:Arial,Helvetica Neue,Helvetica,sans-serif; }';
    $pdf->marginTop = 150;
    $pdf->marginBottom = 50;
    $headers = '<div style="height: 145px;"></div>
   <table class="table table-responsive">
       <tbody>
        <tr class="nospace-border">
            <td width="70%" style="padding: 0px;"></td>
            <td width="30%" style="padding: 0px; padding-left: 15px; padding-top: 0px;">'.$model->purchase_request_referrence_no.'</td>
        </tr>
        <tr class="nospace-border">
            <td width="70%" style="padding: 0px;"></td>
            <td width="30%" style="padding: 0px;padding-left: 15px;">'.$model->purchase_request_project_name.'</td>
        </tr>
        <tr class="nospace-border">
            <td width="70%" style="padding: 0px;"></td>
            <td width="30%" style="padding: 0px;padding-left: 15px;">'.$model->purchase_request_location_project.'</td>
        </tr>
        <tr class="nospace-border">
            <td style="padding: 0px; padding-top: 34px" width="">'.$supplier.'</td>
        </tr>
        <tr class="nospace-border">
            <td style="padding: 0px;" width="">'.$address.'</td>
        </tr>
        <tr class="nospace-border">
            <td style="height: 50px;"></td>
        </tr>
        <tr class="nospace-border">
            <td style="padding: 0px; padding-top:25px; padding-left:50px;" width="">'.date_format($sub, "F j, Y").'</td>
        </tr>

        <tr class="nospace-border">
            <td style="height: 50px;"></td>
        </tr>

        <tr class="nospace-border">
            <td width="80%"></td>
            <td style="padding: -10px; padding-left: -20px; text-align: center; text-decoration: underline;" width="20%">'.$employee[0].'</td>
        </tr>
        <tr class="nospace-border">
            <td width="80%"></td>
            <td style="padding: 0px; padding-left: -20px; text-align: center;" width="20%">Supply Officer</td>
        </tr>
       </tbody>
    </table>
    ';


    $LeftFooterContent = '<div style="text-align: left;font-weight: bold;">' . $model->purchase_request_number . '</div><div style="text-align: left;font-weight: lighter">'.date("F j, Y").'</div>';
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
        'defaultheaderline' => 0,
        'defaultfooterline' => 0,
        'subject' => 'Report Subject'];

    $pdf->methods = [
        'SetHeader' => [$headers],
        'SetFooter' => [$headerFooterConfiguration],
    ];

    return $pdf->render();
}


    public function actionReportabstract()
    {
        $request = Yii::$app->request;
        $id = $request->get('id');
        $model = $this->findModel($id);
        $prdetails = $this->getabstractofbids($model->purchase_request_id);
        $con = Yii::$app->procurementdb;
        $columns = [];
        if (empty($queryres)) {
            $columns = $con->getTableSchema('tmpheader')->getColumnNames();
        }
        $assig = $this->getassig();
        $content = $this->renderPartial('_report_abstract', ['prdetails' => $prdetails, 'model' => $model , 'columns' => $columns]);
        $pdf = new Pdf();
        $pdf->format = [216,330];
        $pdf->orientation = Pdf::ORIENT_LANDSCAPE;
        //$pdf->format = Pdf::FORMAT_LEGAL;
        $pdf->destination = Pdf::DEST_BROWSER;
        $pdf->marginLeft=41;
        $pdf->marginRight=0;
        $pdf->marginTop=43;
        $pdf->marginBottom=5;
        $pdf->defaultFontSize=7;
        $pdf->content = $content;
        $pdf->cssFile = '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css';
        $pdf->cssInline = '.kv-heading-1{font-size:18px}.nospace-border{border:0px;}.no-padding{ padding:0px;}.print-container{font-size:11px;font-family:Arial,Helvetica Neue,Helvetica,sans-serif; }';
        $headers='<table style="padding-right: 150px;text-align: right;" width="100%"> 
            <tr>
                <td style="padding-top: 55px;"></td>
            </tr>
            <tr>
                <td style="font-size: 9px;padding-left: 250px;">'.$model->purchase_request_referrence_no.'</td>
            </tr>
            <tr>
                <td style="font-size: 9px;padding-left: 200px;">'.$model->purchase_request_project_name.'</td>
            </tr>
             <tr>
                <td style="font-size: 9px;padding-left: 200px;">'.$model->purchase_request_location_project.'</td>
            </tr>
        </table>';
        foreach ($assig as $sg) {
           $assig1 =  $sg["Assig1"];
           $assig2 =  $sg["Assig2"];
           $assig3 =  $sg["Assig3"];
           $assig4 =  $sg["Assig4"];
           $assig5 =  $sg["Assig5"];
           $assig6 =  $sg["Assig6"];
           $Assig1Position =  $sg["Assig1Position"];
           $Assig2Position =  $sg["Assig2Position"];
           $Assig3Position =  $sg["Assig3Position"];
           $Assig4Position =  $sg["Assig4Position"];
           $Assig5Position =  $sg["Assig5Position"];
           $Assig6Position =  $sg["Assig6Position"];
        }
        $LeftFooterContent = '
<table width="100%">
    <tr>
        <td style="font-size: 11px;text-align: center; width=16.67"><b>'.$assig1.'</b><br/>Chairperson</td>
        <td style="width: 50px;"></td>
        <td style="font-size: 11px;text-align: center; width=16.67"><b>'.$assig2.'</b><br/>Members</td>
        <td style="width: 50px;"></td>
        <td style="font-size: 11px;text-align: center; width=16.67"><b>'.$assig3.'</b><br/>Members</td>
        <td style="width: 50px;"></td>
        <td style="font-size: 11px;text-align: center; width=16.67"><b>'.$assig4.'</b><br/>Members</td>
        <td style="height: 100px;"></td>
        <td style="font-size: 11px;text-align: center; width=16.67"><b>'.$assig5.'</b><br/>Members</td>
        <td style="height: 100px;"></td>
        <td style="font-size: 11px;text-align: center; width=16.67"><b>'.$assig6.'</b><br/>'.$Assig6Position.'</td>
        <td style="height: 100px;"></td>
    </tr>
        <tr>
        <td style="font-size: 11px;text-align: center; width=16.67">'.date("F j, Y").'</td>
        <td style="width: 50px;"></td>
        <td style="font-size: 11px;text-align: center; width=16.67"></td>
        <td style="width: 50px;"></td>
        <td style="font-size: 11px;text-align: center; width=16.67"></td>
        <td style="width: 50px;"></td>
        <td style="font-size: 11px;text-align: center; width=16.67"></td>
        <td style=""></td>
        <td style="font-size: 11px;text-align: center; width=16.67"></td>
        <td style=""></td>
        <td style="font-size: 11px;text-align: center; width=16.67">Page {PAGENO} of {nbpg}</td>
        <td style=""></td>
    </tr>
</table>
';
        $pdf->options = [
            'title' => 'ABSTRACT OF BIDS',
            'defaultheaderline' => 0,
            'defaultfooterline' => 0,
            'subject' => 'Report Abstract'];
        $pdf->methods = [
            'SetHeader' => [$headers],
            'SetFooter' => [$LeftFooterContent],
        ];

        return $pdf->render();
    }

    public function actionMtest()
    {
        $searchModel = new Purchaserequestsearchdetails();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('_test', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView()
    {
        $request = Yii::$app->request->get();
        if ($request['id'] && $request['view']) {
            $id = $request['id'];
            $returns = $request['view'];
            $model = $this->findModel($id);
            $m = $this->findSupplier();
            $prdetails = $this->getprDetails($model->purchase_request_id);
            $biddetails = $this->getbidDetails($model->purchase_request_id);
            $ListPOprovider = $this->getpoDetails($model->purchase_request_id);
            $searchModel = new Purchaserequestsearchdetails();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            $searchModelBid = new BidsSearch();
            $bidsProvider = $searchModelBid->search(Yii::$app->request->queryParams);
            $dataProvider->query->where('purchase_request_id=' . $model->purchase_request_id);
            switch ($returns) {
                case 'quotation':
                    if (Yii::$app->request->isAjax) {
                        return $this->renderAjax('_quotation', [
                            'model' => $model, 'prdetails' => $prdetails
                        ]);
                    } else {
                        return $this->render('_quotation', [
                            'model' => $model, 'prdetails' => $prdetails
                        ]);
                    }
                    break;
                case 'bids':
                    if (Yii::$app->request->isAjax) {
                        $dataProvider->sort = false;
                        return $this->renderAjax('_bids', [
                            'model' => $model, 'prdetails' => $prdetails, 'biddetails' => $biddetails, 'searchModel' => $searchModel, 'dataProvider' => $dataProvider,
                            'searchModelBid' => $searchModelBid, 'bidsProvider' => $bidsProvider, 'ListPOprovider' => $ListPOprovider , 'supp' => $m
                        ]);
                    } else {
                        return $this->render('_bids', [
                            'model' => $model, 'prdetails' => $prdetails, 'biddetails' => $biddetails, 'searchModel' => $searchModel, 'dataProvider' => $dataProvider,
                            'searchModelBid' => $searchModelBid, 'bidsProvider' => $bidsProvider, 'ListPOprovider' => $ListPOprovider , 'supp' => $m
                        ]);
                    }
                    break;
            }
        }
    }


    function actionRegeneratesupplier () {
        $session = Yii::$app->session;
        $request = Yii::$app->request->post();
        $id = $request["chkRow"];
        $old_bids_id = $session["temp_bids_id"];
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
        if (($model = PurchaseRequest::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function findSupplier()
    {
        if (($model = Supplier::find()->all()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function findbidsdetail($id)
    {
        if (($model = BidsDetails::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    function getprDetails($id)
    {
        $con = Yii::$app->procurementdb;
        $sql = "SELECT * FROM `tbl_purchase_request_details` WHERE `purchase_request_id`=" . $id;
        $porequest = $con->createCommand($sql)->queryAll();
        return $porequest;
    }

    function getpr($id)
    {
        $con = Yii::$app->procurementdb;
        $sql = "SELECT * FROM `tbl_purchase_request_details` WHERE `purchase_request_id`=" . $id;
        $porequest = $con->createCommand($sql)->queryAll();
        return $porequest;
    }

    function getbidDetails($id)
    {
        $con = Yii::$app->procurementdb;
        $sql = "SELECT * FROM `tbl_bids_details` WHERE `purchase_request_id`=" . $id;
        $pordetails = $con->createCommand($sql)->queryAll();
        return $pordetails;
    }

    function getbidDetails2($id)
    {
        $con = Yii::$app->procurementdb;
        $sql = "SELECT * FROM `tbl_bids_details` WHERE `bids_details_id`='" . $id . "' AND bids_details_status<>1";
        $pordetails = $con->createCommand($sql)->queryAll();
        return $pordetails;
    }

    function getpbidDetails($id)
    {
        $con = Yii::$app->procurementdb;
        $sql = "SELECT * FROM `tbl_bids_details` WHERE `purchase_request_details_id`=" . $id;
        $pordetails = $con->createCommand($sql)->queryAll();
        return $pordetails;
    }

    function getabstractofbids($id)
    {
        $con = Yii::$app->procurementdb;
        $sql = "CALL `fais-procurement`.spGenerateAbstractOfBids(" . $id . ",1)";
        $pordetails = $con->createCommand($sql)->queryAll();
        return $pordetails;
    }

    function getassig()
    {
        $con = Yii::$app->db;
        $sql = "SELECT `fais-procurement`.`fnGetAssignatoryName`(`tbl_assignatory`.`assignatory_1`) AS Assig1 , 
	       `fais-procurement`.`fnGetAssignatoryPosition`(`tbl_assignatory`.`assignatory_1`) AS Assig1Position,
	       `fais-procurement`.`fnGetAssignatoryName`(`tbl_assignatory`.`assignatory_2`) AS Assig2 , 
	       `fais-procurement`.`fnGetAssignatoryPosition`(`tbl_assignatory`.`assignatory_2`) AS Assig2Position,
	       `fais-procurement`.`fnGetAssignatoryName`(`tbl_assignatory`.`assignatory_3`) AS Assig3 , 
	       `fais-procurement`.`fnGetAssignatoryPosition`(`tbl_assignatory`.`assignatory_3`) AS Assig3Position,
	       `fais-procurement`.`fnGetAssignatoryName`(`tbl_assignatory`.`assignatory_4`) AS Assig4 , 
	       `fais-procurement`.`fnGetAssignatoryPosition`(`tbl_assignatory`.`assignatory_4`) AS Assig4Position,
	       `fais-procurement`.`fnGetAssignatoryName`(`tbl_assignatory`.`assignatory_5`) AS Assig5 , 
	       `fais-procurement`.`fnGetAssignatoryPosition`(`tbl_assignatory`.`assignatory_5`) AS Assig5Position,
	       `fais-procurement`.`fnGetAssignatoryName`(`tbl_assignatory`.`assignatory_6`) AS Assig6 , 
	       `fais-procurement`.`fnGetAssignatoryPosition`(`tbl_assignatory`.`assignatory_6`) AS Assig6Position
	FROM `tbl_assignatory`
	WHERE `tbl_assignatory`.`assignatory_id` = 4
";
        $pordetails = $con->createCommand($sql)->queryAll();
        return $pordetails;
    }



    function getpoDetails($id)
    {
        $con = Yii::$app->procurementdb;
        $sql = "CALL `fais-procurement`.spGenerateListPO(" . $id . ",0)";
        $pordetails = $con->createCommand($sql)->queryAll();
        $x = 0;
        foreach ($pordetails as $pr) {
            $x++;
            $data[] = ['bids_id' => $pr["bids_id"],
                'supplier_id' => $pr["supplier_id"],
                'SupplierName' => $pr["SupplierName"],
                'purchase_order_number' => $pr["purchase_order_number"],
                'bids_unit' => $pr["bids_unit"],
                'bids_item_description' => $pr["bids_item_description"],
                'bids_quantity' => $pr["bids_quantity"],
                'bids_price' => $pr["bids_price"],
            ];
        }
        if ($x == 0) {
            $data[] = ['bids_id' => '',
                'supplier_id' => '',
                'SupplierName' => '',
                'purchase_order_number' => '',
                'bids_unit' => '',
                'bids_item_description' => '',
                'bids_quantity' => '',
                'bids_price' => '',
            ];
        }

        $provider = new ArrayDataProvider([
            'allModels' => $data,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'attributes' => ['bids_id',
                    'supplier_id',
                    'SupplierName',
                    'purchase_order_number',
                    'bids_unit',
                    'bids_item_description',
                    'bids_quantity',
                    'bids_price',
                ],
            ],
        ]);
        $pordetails = $provider;
        return $pordetails;
    }


    function checkbidDetailStatus ($id) {
        $con = Yii::$app->procurementdb;
        $sql = "SELECT count(*) AS ifexist FROM `tbl_bids_details` WHERE `tbl_bids_details`.`bids_details_status` =3 AND `tbl_bids_details`.`bids_details_id`=".$id;
        $pordetails = $con->createCommand($sql)->queryAll();
        return $pordetails;
    }


}