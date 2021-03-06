<?php

use yii\helpers\Html;
use kartik\editable\Editable; 
use kartik\grid\GridView;

use yii\helpers\Url;
use yii\helpers\ArrayHelper;

use common\models\cashier\Creditortype;

/* @var $this yii\web\View */
/* @var $searchModel common\models\cashier\CreditorSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Creditors';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="creditor-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Creditor', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    
          <?php
        echo GridView::widget([
            'id' => 'request',
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'containerOptions' => ['style' => 'overflow-x: none!important','class'=>'kv-grid-container'], // only set when $responsive = false
            'headerRowOptions' => ['class' => 'kartik-sheet-style'],
            'filterRowOptions' => ['class' => 'kartik-sheet-style'],
            'bordered' => true,
            'striped' => true,
            'condensed' => true,
            'responsive' => false,
            'hover' => true,
            'columns' => [
                            /*[
                                'attribute'=>'request_id',
                                'contentOptions' => ['style' => 'padding-left: 25px; font-weigth: bold;'],
                                'width'=>'80px',
                                'value'=>function ($model, $key, $index, $widget) { 
                                    return $model->request->request_number;
                                },
                            ],*/
                            [
                                'attribute'=>'name',
                                'header'=>'Name',
                                'headerOptions' => ['style' => 'text-align: center;'],
                                'contentOptions' => ['style' => 'text-align: left; padding-left: 10px;'],
                                'width'=>'150px',
                                'format'=>'raw',
                                'value'=>function ($model, $key, $index, $widget) { 
                                    //return isset($model->os->os_id) ? $model->os->os_number.'<br/>'.$model->os->os_date : '';
                                    return $model->name;
                                },
                            ],
                            [
                                'attribute'=>'creditor_type_id',
                                'header'=>'Creditor Type',
                                'headerOptions' => ['style' => 'text-align: center;'],
                                'contentOptions' => ['style' => 'text-align: left; padding-left: 10px;'],
                                'width'=>'150px',
                                //'format'=>'raw',
                                'value'=>function ($model, $key, $index, $widget) { 
                                    //return isset($model->os->os_id) ? $model->os->os_number.'<br/>'.$model->os->os_date : '';
                                    return $model->type->name;
                                },
                                'filterType' => GridView::FILTER_SELECT2,
                                'filter' => ArrayHelper::map(Creditortype::find()->asArray()->all(), 'creditor_type_id', 'name'), 
                                'filterWidgetOptions' => [
                                    'pluginOptions' => ['allowClear' => true],
                                ],
                                'filterInputOptions' => ['placeholder' => 'Select Type', 'multiple' => false], // allows multiple authors to be chosen
                                //'filterInputOptions' => ['placeholder' => 'Select Type','id' => 'grid-search-request_type_id'],
                                'format' => 'raw'
                            ],
                            [
                                'attribute'=>'account_number',
                                'header'=>'Account Number',
                                'headerOptions' => ['style' => 'text-align: center;'],
                                'contentOptions' => ['style' => 'text-align: center;'],
                                'width'=>'150px',
                                'value'=>function ($model, $key, $index, $widget) { 
                                    //return isset($model->dv->dv_id) ? $model->dv->dv_number : '';
                                    return Yii::$app->user->can('access-cashiering') ? $model->account_number : '-';
                                },
                            ],
                            [
                                'class'=>'kartik\grid\EditableColumn',
                                'attribute'=>'tin_number',
                                'header'=>'TIN',
                                //'width'=>'350px',
                                'refreshGrid'=>true,
                                //'readonly' => !$isMember,
                                'value' => function($model , $key , $index){
                                            return $model->tin_number;
                                        },
                                'editableOptions'=> function ($model , $key , $index) {
                                    return [
                                        'options' => ['id' => $index . '_10_' . $model->tin_number],
                                        'placement'=>'left',
                                        //'disabled'=>!Yii::$app->user->can('access-finance-disbursement'),
                                        //'disabled'=>true,
                                        'name'=>'tin_number',
                                        'asPopover' => true,
                                        'value' => function($model , $key , $index){
                                            return $model->tin_number;
                                        },
                                        //'inputType' => \kartik\editable\Editable::INPUT_TEXT,
                                        'inputType' => \kartik\editable\Editable::INPUT_TEXT,
                                        /*'options' => [
                                            'pluginOptions' => ['min' => 0, 'max' => 5000000]
                                        ],*/
                                        'formOptions'=>['action' => ['/cashier/creditor/updatetin']], // point to the new action
                                    ];
                                },
                                'headerOptions' => ['style' => 'text-align: center'],
                                'contentOptions' => ['style' => 'text-align: center'],
                                'hAlign'=>'right',
                                //'vAlign'=>'middle',
                                'width'=>'250px',
                                'pageSummary' => true
                            ],
                            /*[
                                'class' => kartik\grid\ActionColumn::className(),
                                'template' => '{view}',
                                'buttons' => [
                                    'view' => function ($url, $model){
                                        return Html::button('<span class="glyphicon glyphicon-eye-open"></span>', ['value' => '/finance/osdv/view?id=' . $model->creditor_id,'onclick'=>'location.href=this.value', 'class' => 'btn btn-primary', 'title' => Yii::t('app', "View Request")]);
                                    },
                                ],
                            ],*/
                    ],
            'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
            'headerRowOptions' => ['class' => 'kartik-sheet-style'],
            'filterRowOptions' => ['class' => 'kartik-sheet-style'],
            'panel' => [
                    'heading' => '',
                    'type' => GridView::TYPE_PRIMARY,
                    'before'=> '',/*Html::button('Validated Requests  &nbsp;&nbsp;<span class="badge badge-light">'.$numberOfRequests.'</span>', ['value' => Url::to(['osdv/create']), 'title' => 'Request', 'class' => 'btn btn-success', 'style'=>'margin-right: 6px;', 'id'=>'buttonCreateOsdv']),*/
                    'after'=>false,
                ],
            'pjax' => true, // pjax is set to always true for this demo
            // set your toolbar
            'toolbar' => 
                        [
                            [
                                'content'=>'',
                                    /*Html::button('PENDING', ['title' => 'Approved', 'class' => 'btn btn-warning', 'style'=>'width: 90px; margin-right: 6px;']) .    
                                    Html::button('SUBMITTED', ['title' => 'Approved', 'class' => 'btn btn-primary', 'style'=>'width: 90px; margin-right: 6px;']) .
                                    Html::button('APPROVED', ['title' => 'Approved', 'class' => 'btn btn-success', 'style'=>'width: 90px; margin-right: 6px;'])*/
                            ],
                            //'{export}',
                            //'{toggleData}'
                        ],
            
            'toggleDataOptions' => ['minCount' => 10],
            //'exportConfig' => $exportConfig,
            'itemLabelSingle' => 'item',
            'itemLabelPlural' => 'items'
        ]);
        ?>
</div>
