<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use kartik\widgets\DatePicker;
use kartik\datetime\DateTimePicker;
use yii\helpers\Url;

use common\models\cashier\Creditor;
use common\models\finance\Requesttype;
use common\models\finance\Obligationtype;
use common\models\procurement\Division;
/* @var $this yii\web\View */
/* @var $model common\models\finance\Request */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="request-form">

    <?php $form = ActiveForm::begin(); ?>

    <!--?= $form->field($model, 'request_number')->textInput() ?-->

    <!--?= $form->field($model, 'request_date')->textInput() ?-->
    
    <div class="row">
        
        <div class="col-md-6"> 
                <h5 data-step="1" data-intro="Select Request type.">
                <?= $form->field($model, 'request_type_id')->widget(Select2::classname(), [
                    'data' => ArrayHelper::map(Requesttype::find()->where('active =:active',[':active'=>1])->all(),'request_type_id','name'),
                    'language' => 'en',
                    'options' => ['placeholder' => 'Select Request Type','readonly'=>'readonly'],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    'pluginEvents'=>[
                        "change" => 'function() { 
                            var requestTypeId=this.value;
                            $.post("/finance/request/updateparticulars/", 
                                {
                                    requestTypeId: requestTypeId
                                }, 
                                function(response){
                                    if(response){
                                       $("#request-particulars").val(response.default_text);
                                       //alert(response.default_text);
                                    }
                                }
                            );
                        }
                    ',]
                ])->label('Request Type'); ?>
                </h5>
        </div>
        
        <div class="col-md-6">
            <?= $form->field($model, 'request_date')->widget(DateTimePicker::classname(), [
                'readonly' => true,
                'disabled' => true,
                'options' => ['placeholder' => 'Select Date'],
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd hh:ii:ss'
                ]
            ])->label('Request Date');?>
        </div>
        
    </div>
    
    <div class="row">
        <div class="col-md-6"> 
                <h5 data-step="2" data-intro="Specify Source of Fund.">
                <?= $form->field($model, 'obligation_type_id')->widget(Select2::classname(), [
                    'data' => ArrayHelper::map(Obligationtype::find()->all(),'type_id','name'),
                    'language' => 'en',
                    //'options' => ['placeholder' => 'Select Request Type','readonly'=>'readonly'],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    /*'pluginEvents'=>[
                        "change" => 'function() { 
                            var requestTypeId=this.value;
                            $.post("/finance/request/updateparticulars/", 
                                {
                                    requestTypeId: requestTypeId
                                }, 
                                function(response){
                                    if(response){
                                       $("#request-particulars").val(response.default_text);
                                       //alert(response.default_text);
                                    }
                                }
                            );
                        }
                    ',]*/
                ])->label('Fund Source'); ?>
            </h5>
        </div>
        
        <div class="col-md-6">
                <h5 data-step="4" data-intro="Select Division.">
                <?= $form->field($model, 'division_id')->widget(Select2::classname(), [
                    'data' => ArrayHelper::map(Division::find()->all(),'division_id','name'),
                    'language' => 'en',
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                ])->label('Division'); ?>
                </h5>
        </div>
    </div>
    <h5 data-step="5" data-intro="Search for Payee / Creditor.">
    <?= $form->field($model, 'payee_id')->widget(Select2::classname(), [
                    'data' => ArrayHelper::map(Creditor::find()->orderBy(['name'=>SORT_ASC])->all(),'creditor_id','name'),
                    'language' => 'en',
                    'options' => ['placeholder' => 'Select Payee','readonly'=>'readonly'],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
])->label('Payee / Creditor'); ?></h5>

    <h5 data-step="6" data-intro="Indicate the details of this financial request.">
    <?= $form->field($model, 'particulars')->textarea(['rows' => 6]) ?>
    </h5>
    <h5 data-step="7" data-intro="Enter amount.">
    <?= $form->field($model, 'amount')->textInput() ?>
    </h5>
    <div class="form-group">
        <h5 data-step="7" data-intro="Hit Create to proceed.">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </h5>
        
        <a id="startButton"  href="javascript:void(0);">Show guide</a>
    </div>

    <?php ActiveForm::end(); ?>
    
</div>
<script type="text/javascript">
    document.getElementById('startButton').onclick = function() {
        introJs().setOption('doneLabel', 'Next page').start().oncomplete(function() {
            window.location.href = 'index?multipage=true';
        });
    };
</script>