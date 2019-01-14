<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\procurement\Disbursement */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="disbursement-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'dv_no')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'dv_date')->textInput() ?>

    <?= $form->field($model, 'particulars')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'payee')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'dv_amount')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'dv_total')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'certified_a')->textInput() ?>

    <?= $form->field($model, 'certified_b')->textInput() ?>

    <?= $form->field($model, 'approved')->textInput() ?>

    <?= $form->field($model, 'os_no')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'taxable')->textInput() ?>

    <?= $form->field($model, 'dv_type')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'po_no')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'funding_id')->textInput() ?>

    <?= $form->field($model, 'fundings')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'supporting_docs')->textInput() ?>

    <?= $form->field($model, 'cash_available')->textInput() ?>

    <?= $form->field($model, 'subject_ada')->textInput() ?>

    <?= $form->field($model, 'user_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
