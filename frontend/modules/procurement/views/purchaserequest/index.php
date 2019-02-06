<?php

/**
 * Created by Larry Mark B. Somocor.
 * User: Larry
 * Date: 3/13/2018
 * Time: 9:47 AM
 */


use yii\helpers\Html;
use common\modules\pdfprint;

use common\components\Functions;
use yii2mod\alert\Alert;


$func = new Functions();

/* @var $this yii\web\View */
/* @var $searchModel common\models\procurement\PurchaserequestSearch */
/* @var $model common\models\procurement\Lineitembudget */
/* @var $dataProvider yii\data\ActiveDataProvider */


$BaseURL = $GLOBALS['frontend_base_uri'];
$this->title = 'Purchase Request';
$angularcontroller = "";
$this->params['breadcrumbs'][] = '';
//$this->registerJsFile('https://code.jquery.com/ui/1.12.1/jquery-ui.js');
$this->registerJsFile($BaseURL.'js/angular.min.js');
$this->registerJsFile($BaseURL.'js/ui-bootstrap-tpls-0.10.0.min.js');
$this->registerJsFile($BaseURL.'js/jquery.tabletojson.js');
$this->registerJsFile($BaseURL.'js/app.js');
$this->registerJsFile($BaseURL.'js/custom.js');
?>

<div class="request-index">
    <h1 class="centered"><i class="fa fa-cart-plus"></i> <?= Html::encode($this->title) ?></h1>
    <?php
    //Generate Header Controller AngularJS
    $maincontroller=str_replace(" ", "",strtolower(Html::encode($this->title)))."ctrl";?>
    <?=
    //Generate AngularJS Header
    $func->GridHeaderAngularJS($maincontroller,"myAdd","Create New Purchase Request");?>
    <?= $func->GridHeader('Request #','purchase_request_number'); ?>
    <?= $func->GridHeader('Request Purpose ','purchase_request_purpose'); ?>
    <?= $func->GridHeader('Division ','division_name'); ?>
    <?= $func->GridHeader('Section ','section_name'); ?>
    <?= $func->GridHeader('Action ',''); ?>
    <?= //Close The AngularJS Header
        $func->GridHeaderAngularJSClose();
    ?>
    <!-- *********************************** Generate Header Grid Details ************************************************ -->
    <?=
    $func->GridHeaderDetails();
    ?>
    <!-- *********************************** Generate Grid Details ************************************************ -->
    <?= $func->GridDetails('purchase_request_number');  ?>
    <?= $func->GridDetails('purchase_request_purpose'); ?>
    <?= $func->GridDetails('division_name');  ?>
    <?= $func->GridDetails('section_name');  ?>

    <!-- *********************************** Start Group for Buttons ************************************************ -->

    <?= $func->GridGroupStart('button-control')?>
    <a href="reportpr?id={{data.purchase_request_id}}" class="btn-pdfprint btn btn-warning grdbutton"> <i class="fa fa-print"></i></a>
    <?= $func->GridButton('purchase_request_id',"","btnDelete","danger","","grdbutton","fa fa-minus","Delete","procurement/purchaserequest/") ?>
    <?= $func->GridButton('purchase_request_id',"","btnEdit","default ","","grdbutton","fa fa-edit","Update","myEdit") ?>
    <?= $func->GridButton('purchase_request_id',"","btnView","primary","","grdbutton", "fa fa-eye","myView","myView") ?>
    <?= $func->GridGroupEnd();?>
    <!-- *********************************** Close Group for Buttons ************************************************ -->
    <?=
    $func->GridHeaderClose();
    ?>
    <!-- *********************************** Close Grid Details ************************************************ -->

    <!-- *********************************** Generate Header Modal for Create ************************************************ -->
    <?= $func->GenerateHeaderModal("myAdd","Request Module",'65',0) ?>
    <div class="request-create">
        <div class="loadpartial">
            <img src="<?= $BaseURL; ?>/images/loading.gif">
        </div>
        <div id="mycreate">
        </div>
    </div>
    <?=
    $func->GenerateFooterModal("Close","Proceed",0);
    ?>
    <!-- *********************************** Generate Footer Modal ************************************************ -->

    <!-- *********************************** Generate Header Modal for Create ************************************************ -->
    <?= $func->GenerateHeaderModal("Update","Request Module",'60',0) ?>
    <div class="request-update">
        <div class="loadpartial">
            <img src="<?= $BaseURL; ?>/images/loading.gif">
        </div>
        <div id="mycontent">
        </div>
    </div>
    <?=
    $func->GenerateFooterModal("Close","Proceed",0);
    ?>
    <!-- *********************************** Generate Footer Modal ************************************************ -->

    <!-- *********************************** Generate Header Modal for View ************************************************ -->
    <?= $func->GenerateHeaderModal("myView","Request Module",'35',10) ?>
    <div class="request-view">
        <div class="loadpartial">
            <img src="<?= $BaseURL; ?>/images/loading.gif">
        </div>
        <div id="mycontentview">
        </div>
    </div>
    <?=
    // This function will close the footer of the modal
    $func->GenerateFooterModal("Close","Proceed",0);
    ?>
    <!-- *********************************** Generate Footer Modal ************************************************ -->

    <!-- *********************************** Close for View ************************************************ -->

    <?php
    // This section will allow to popup a notification
    $session = Yii::$app->session;
    if ($session->isActive) {
        $session->open();
        if (isset($session['deletepopup'])) {
            $func->CrudAlert("Deleted Successfully",Alert::TYPE_WARNING);
            unset($session['deletepopup']);
            $session->close();
        }
        if (isset($session['updatepopup'])) {
            $func->CrudAlert("Updated Successfully");
            unset($session['updatepopup']);
            $session->close();
        }
        if (isset($session['savepopup'])) {
            $func->CrudAlert("Saved Successfully",Alert::TYPE_SUCCESS,true);
            unset($session['savepopup']);
            $session->close();
        }
        if (isset($session['errorpopup'])) {
            $func->CrudAlert("Error Transaction",Alert::TYPE_WARNING,true);
            unset($session['errorpopup']);
            $session->close();
        }
    }
    ?>
    <?= pdfprint\Pdfprint::widget([
        'elementClass' => '.btn-pdfprint'
    ]); ?>

</div>


