<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\procurement\Disbursement */

$this->title = $model->dv_id;
$this->params['breadcrumbs'][] = ['label' => 'Disbursements', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="disbursement-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->dv_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->dv_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'dv_id',
            'dv_no',
            'dv_date',
            'particulars:ntext',
            'payee',
            'address',
            'dv_amount',
            'dv_total',
            'certified_a',
            'certified_b',
            'approved',
            'os_no',
            'taxable',
            'dv_type',
            'po_no',
            'funding_id',
            'fundings',
            'supporting_docs',
            'cash_available',
            'subject_ada',
            'user_id',
        ],
    ]) ?>

</div>
