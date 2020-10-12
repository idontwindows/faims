<?php
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use kartik\widgets\DatePicker;
use kartik\editable\Editable; ;
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
                    'data' => ArrayHelper::map(Obligationtype::find()->all(),'type_id','name', 'fundcategory.name'),
                    'language' => 'en',
                    //'theme' => Select2::THEME_DEFAULT,
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
    
    
    <div class="row">
    <div class="col-md-12">
        <h5 data-step="5" data-intro="Search Or request to add new Payee / Creditor.">
        <div class="input-group">
                       <?= $form->field($model, 'payee_id')->widget(Select2::classname(), [
                        'data' => ArrayHelper::map(Creditor::find()->orderBy(['name'=>SORT_ASC])->all(),'creditor_id','name'),
                        'language' => 'en',
                        'options' => ['placeholder' => 'Select Payee','readonly'=>'readonly'],
                        'pluginOptions' => [
                            'allowClear' => false
                        ],
                        ])->label('Payee / Creditor'); ?>
                        
                        <span class="input-group-btn" style="padding-top: 20px; padding-left: 5px;">
                            <!--button onclick="LoadModal('Create New Payee', '/finance/info/addcreditor');" id="buttonAddCreditor" class="btn btn-default" type="button"><i class="fa fa-address-card-o"></i></button-->
                            <?= Html::button('<i class="fa fa-address-card-o"></i>', ['value' => Url::to(['/cashier/creditortmp/create']), 'title' => 'Create Payee / Creditor', 'class' => 'btn btn-info', 'style'=>'margin-right: 6px;', 'id'=>'buttonAddCreditor']) ?>
                        </span>
        </div>
        </h5>
    </div>
    </div>
    
    
    <?php 
        /*$editable = Editable::begin([
            //'model'=>$newcreditor_model,
            //'attribute'=>'name',
            'name'=>'payee',
            'value'=>'New Payee',
            'format'=>'button',
            'asPopover' => false,
            'size'=>'sm',
            //'displayValue' => 'Add New Payee / Creditor',
            'options'=>['placeholder'=>'Creditor Name'],
            //'formOptions'=>['action' => ['/finance/request/addcreditor']], // point to the new action
        ]);
        $form2 = $editable->getForm();
        //echo Html::hiddenInput('kv-complex', '1');
        //echo Html::hiddenInput('kv-complex', '1');
        $editable->afterInput = 
            '&nbsp;&nbsp;' .$form2->field($newcreditor_model, 'address')->textInput(['placeholder'=>'Address'])->label(false) . '&nbsp;&nbsp;&nbsp;' .
            $form2->field($newcreditor_model, 'tin_number')->textInput(['placeholder'=>'Tin Number'])->label(false);
        Editable::end();*/
    ?>
        
    <h5 data-step="6" data-intro="Indicate the details of this financial request.">
    <?= $form->field($model, 'particulars')->textarea(['rows' => 6]) ?>
    </h5>
    <h5 data-step="7" data-intro="Enter amount.">
    <?= $form->field($model, 'amount')->textInput() ?>
    </h5>
    <div class="form-group">
        <h5 data-step="8" data-intro="Hit Create to proceed.">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </h5>
        
        <h5 data-step="9" data-intro="You are Golden! Thank you for using this guide! Take a screenshot and post it in the DOST IX Official Communication Portal. Tag ADM to claim your P100 load card! 1 winner only :-)">
            <a id="startButton"  href="javascript:void(0);">Show guide</a>
        </h5>
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