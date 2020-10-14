<?php
use kartik\grid\GridView;
?>

<?= GridView::widget([
'dataProvider' => $dataProvider,
//'filterModel' => $searchModel,
'id'=>'expenditure_objects', //additional
'pjax' => true, // pjax is set to always true for this demo
'pjaxSettings' => [
        'options' => [
            'enablePushState' => false,
        ]
    ],
'columns' => [
    [
            'class' => 'kartik\grid\SerialColumn',
            //'contentOptions' => ['class' => 'kartik-sheet-style'],
            //'width' => '20px',
            'header' => '',
            'headerOptions' => ['class' => 'kartik-sheet-style'],
            //'mergeHeader' => true,
        ],
    [
        'attribute' => 'message',
        'value'=>function ($model, $key, $index, $widget){ 
                    return $model->message;
                },
    ],
]]); ?>