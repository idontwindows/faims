<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use kartik\date\DatePicker;

use common\models\cashier\Lddapada;
/* @var $this yii\web\View */
/* @var $model common\models\cashier\Lddapada */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="lddapada-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'batch_number')->textInput(['value' => Lddapada::generateBatchNumber(),'maxlength' => true]) ?>

    <?= DatePicker::widget([
            'model' => $model, 
            'attribute' => 'batch_date',
            'type' => DatePicker::TYPE_COMPONENT_APPEND,
            'options' => ['placeholder' => 'Set Batch Date', 'value' => date("Y-m-d",strtotime("now"))],
            'pluginOptions' => [
                'autoclose'=>true,
                'format' => "yyyy-mm-dd",
            ]
        ]); ?>
    
    

    <?= $form->field($model, 'certified_correct_id')->textInput(['value' => 1]) ?>

    <?= $form->field($model, 'approved_id')->textInput(['value' => 1]) ?>

    <?= $form->field($model, 'validated1_id')->textInput(['value' => 1]) ?>

    <?= $form->field($model, 'validated2_id')->textInput(['value' => 1]) ?>
    
    <?= $form->field($model, 'create_by')->textInput(['value' => 'No Admin']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
