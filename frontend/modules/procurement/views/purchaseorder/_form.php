<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\widgets\SwitchInput;
use common\models\procurement\UnitType;
use yii\helpers\ArrayHelper;

use dosamigos\ckeditor\CKEditor;

/* @var $this yii\web\View */
/* @var $model common\models\procurement\Obligationrequest */
/* @var $form yii\widgets\ActiveForm */

$BaseURL = $GLOBALS['frontend_base_uri'];
$units = UnitType::find()->all();
$listUnits = ArrayHelper::map($units,'unit_type_id','name_short');

$this->registerJsFile('https://code.jquery.com/ui/1.12.1/jquery-ui.js');
    
?>

<div class="purchaseorder-form">

    <div class="panel panel-default disabled" id="mPanel">
        <div class="panel-body">

            <?php   $form = ActiveForm::begin(['id' => 'purchaseorder-form', 'enableClientValidation' => true,'action' => 'update','method' => 'post']); ?>

            <div class="row">
                <div class="col-lg-6">
                    
                <?= $form->field($model, 'bids_item_description')->widget(CKEditor::className(), [
                    'options' => ['rows' => 6],
                    'preset' => 'full'
                ])->label('Item Description') ?>
            </div>
            <div class="col-lg-6">
                <div class="col-lg-12">
                <label>Unit</label>
                    <?=
                    $form->field($model, 'bids_unit')->widget(Select2::className(),[
                                            'data' => $listUnits,
                                            'value' => $model->bids_unit,
                                            'options' => ['placeholder' => 'Select Unit Type','tabindex'=>0,],
                                            'pluginEvents' => [
                                                "change" => "function() {
                                                                 var data=$(this).val();
                                                        }",
                                            ],
                                        ]);
                    ?>






                </div>
                <div class="col-lg-12">
                     <?= $form->field($model, 'bids_quantity')->textInput(['maxlength' => true,'placeholder'=>'0'])->label('Quantity') ?> 
                </div>
                <div class="col-lg-12">
                     <?= $form->field($model, 'bids_price')->textInput(['maxlength' => true,'placeholder'=>'0'])->label('Price'); ?> 
                </div>    
            </div>
        </div>
                
        
            <div class="space-20"></div>
            <div class="row" style="text-align: right;">  
                <div class="col-lg-12">
                    <div class="form-group">
                        <?= Html::submitButton($model->isNewRecord ? 'Save' : 'Update', ['id'=>$model->isNewRecord ? "btnSave" : "btnUpdate",'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary','name'=> $model->isNewRecord ? "btnSave" : "btnUpdate"]) ?>
                    </div>
                </div>
            </div>
            <?php ActiveForm::end(); ?>


           <!-- Panel End -->

        </div>
    </div>

</div>
