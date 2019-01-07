<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model common\models\inventory\Products */

?>
<div class="products-view">

    <div class="row">
        <div class="col-sm-9">
            <h2><?= Html::encode($model->product_id) ?></h2>
        </div>
    </div>

    <div class="row">
<?php 
    $gridColumn = [
        'product_id',
        'product_code',
        'product_name',
        'description:ntext',
        'price',
        'srp',
        [
            'attribute' => 'categoryType.categorytype_id',
            'label' => 'Category Type',
        ],
        'qty_reorder',
        'qty_onhand',
        'qty_min_reorder',
        'qty_per_unit',
        'discontinued',
        'suppliers_ids:ntext',
    ];
    echo DetailView::widget([
        'model' => $model,
        'attributes' => $gridColumn
    ]); 
?>
    </div>
</div>