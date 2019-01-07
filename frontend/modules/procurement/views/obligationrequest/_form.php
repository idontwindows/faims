<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\procurement\Obligationrequest */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="obligationrequest-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'os_no')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'os_date')->textInput() ?>

    <?= $form->field($model, 'particulars')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'ppa')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'account_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'amount')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'payee')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'office')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'requested_by')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'requested_bypos')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'funds_available')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'funds_available_pos')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'purchase_no')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'os_type')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'dv_no')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
