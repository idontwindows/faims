<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\procurement\Obligationrequest */

$this->title = $model->obligation_request_id;
$this->params['breadcrumbs'][] = ['label' => 'Obligationrequests', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="obligationrequest-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->obligation_request_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->obligation_request_id], [
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
            'obligation_request_id',
            'os_no',
            'os_date',
            'particulars:ntext',
            'ppa',
            'account_code',
            'amount',
            'payee',
            'office',
            'address',
            'requested_by',
            'requested_bypos',
            'funds_available',
            'funds_available_pos',
            'purchase_no',
            'os_type',
            'dv_no',
            'username',
        ],
    ]) ?>

</div>
