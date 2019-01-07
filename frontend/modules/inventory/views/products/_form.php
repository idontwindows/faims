<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\inventory\Categorytype;
use common\models\inventory\Suppliers;
/* @var $this yii\web\View */
/* @var $model common\models\inventory\Products */
/* @var $form yii\widgets\ActiveForm */

\mootensai\components\JsBlock::widget(['viewFile' => '_script', 'pos'=> \yii\web\View::POS_END, 
    'viewParams' => [
        'class' => 'InventoryEntries', 
        'relID' => 'inventory-entries', 
        'value' => \yii\helpers\Json::encode($model->inventoryEntries),
        'isNewRecord' => ($model->isNewRecord) ? 1 : 0
    ]
]);
\mootensai\components\JsBlock::widget(['viewFile' => '_script', 'pos'=> \yii\web\View::POS_END, 
    'viewParams' => [
        'class' => 'InventoryWithdrawaldetails', 
        'relID' => 'inventory-withdrawaldetails', 
        'value' => \yii\helpers\Json::encode($model->inventoryWithdrawaldetails),
        'isNewRecord' => ($model->isNewRecord) ? 1 : 0
    ]
]);
?>

<div class="products-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->errorSummary($model); ?>
     <?= $form->field($model, 'product_id')->hiddenInput()->label(false) ?>
    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'product_code')->textInput(['maxlength' => true, 'placeholder' => 'Product Code']) ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'product_name')->textInput(['maxlength' => true, 'placeholder' => 'Product Name']) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'srp')->textInput(['maxlength' => true, 'placeholder' => 'Srp']) ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'price')->textInput(['maxlength' => true, 'placeholder' => 'Price']) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
             <?= $form->field($model, 'qty_min_reorder')->textInput(['placeholder' => 'Qty Min Reorder']) ?>
        </div>
        <div class="col-sm-6">
            <?=
            $form->field($model, 'category_type_id')->widget(\kartik\widgets\Select2::classname(), [
                'data' => ArrayHelper::map(Categorytype::find()->orderBy('categorytype')->asArray()->all(), 'categorytype_id', 'categorytype'),
                'options' => ['placeholder' => 'Choose CategoryType'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'qty_reorder')->textInput(['placeholder' => 'Qty Reorder']) ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'qty_onhand')->textInput(['placeholder' => 'Qty Onhand']) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
             <?= $form->field($model, 'discontinued')->checkbox() ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'qty_per_unit')->textInput(['maxlength' => true, 'placeholder' => 'Qty Per Unit']) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>
        </div>
        <div class="col-sm-6">
            <?=
            $form->field($model, 'suppliers_ids')->widget(\kartik\widgets\Select2::classname(), [
                'data' => ArrayHelper::map(Suppliers::find()->orderBy('suppliers')->asArray()->all(), 'suppliers_id', 'suppliers'),
                'options' => ['placeholder' => 'Choose Suppliers','multiple'=>true],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);
            ?>
        </div>
    </div>
    <?php
    $forms = [
        [
            'label' => '<i class="glyphicon glyphicon-book"></i> ' . Html::encode('Inventory Entries'),
            'content' => $this->render('_formInventoryEntries', [
                'row' => \yii\helpers\ArrayHelper::toArray($model->inventoryEntries),
            ]),
        ],
        [
            'label' => '<i class="glyphicon glyphicon-book"></i> ' . Html::encode('Inventory Withdrawal Details'),
            'content' => $this->render('_formInventoryWithdrawaldetails', [
                'row' => \yii\helpers\ArrayHelper::toArray($model->inventoryWithdrawaldetails),
            ]),
        ],
    ];
    echo kartik\tabs\TabsX::widget([
        'items' => $forms,
        'position' => kartik\tabs\TabsX::POS_ABOVE,
        'encodeLabels' => false,
        'pluginOptions' => [
            'bordered' => true,
            'sideways' => true,
            'enableCache' => false,
        ],
    ]);
    ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Cancel'), Yii::$app->request->referrer , ['class'=> 'btn btn-danger']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
