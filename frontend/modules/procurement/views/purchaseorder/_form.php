<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\widgets\SwitchInput;

/* @var $this yii\web\View */
/* @var $model common\models\procurement\Obligationrequest */
/* @var $form yii\widgets\ActiveForm */

$BaseURL = $GLOBALS['frontend_base_uri'];



?>

<div class="purchaseorder-form">



    <div class="panel panel-default disabled" id="mPanel">
        <div class="panel-body">

            <!-- Panel Start -->
            <h5><a id="startButton2"  href="javascript:void(0);"><img src="<?= $BaseURL;?>images\help.png" height="52" width="98" style="padding: 10px;"></a></h5>

            <?php   $form = ActiveForm::begin(['id' => 'purchaseorder-form', 'enableClientValidation' => true]); ?>

        
            <div class="space-20"></div>
            <div class="row" style="text-align: right;">
                <div class="col-lg-12">
                    <div class="form-group">
                        <?= Html::submitButton($model->isNewRecord ? 'Save' : 'Update', ['id'=>$model->isNewRecord ? "btnSave" : "btnUpdate",'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary','name'=> $model->isNewRecord ? "btnSave" : "btnUpdate"]) ?>
                        <?= Html::submitButton($model->isNewRecord ? 'Save & Print' : 'Update & Print', ['id'=>$model->isNewRecord ? "btnSavePrint" : "btnUpdatePrint",'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary','name'=> $model->isNewRecord ? "btnSavePrint" : "btnUpdatePrint"]) ?>
                    </div>
                </div>
            </div>
            <?php ActiveForm::end(); ?>


           <!-- Panel End -->

        </div>
    </div>

</div>
