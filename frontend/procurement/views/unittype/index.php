<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\procurement\UnittypeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Unittypes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="unittype-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Unittype', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'unit_type_id',
            'name_short',
            'name_long',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
