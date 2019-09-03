<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\procurement\AssignatorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Assignatories';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="assignatory-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Assignatory', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
        'headerRowOptions' => ['class' => 'kartik-sheet-style'],
        'filterRowOptions' => ['class' => 'kartik-sheet-style'],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'CompanyTitle',
            'RegionOffice',
            'Address',
            'report_title',
            // 'assignatory_1',
            // 'assignatory_2',
            // 'assignatory_3',
            // 'assignatory_4',
            // 'assignatory_5',
            // 'assignatory_6',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
