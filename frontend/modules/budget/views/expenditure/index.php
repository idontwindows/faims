<?php

use kartik\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

use kartik\datecontrol\DateControl;
use kartik\detail\DetailView;
use kartik\editable\Editable; 
use kartik\grid\GridView;

use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $searchModel common\models\procurementplan\ExpenditureSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

// Modal Expenditure
Modal::begin([
    'header' => '<h4 id="modalHeader" style="color: #ffffff"></h4>',
    'id' => 'modalExpenditure',
    'size' => 'modal-md',
    'options'=> [
             'tabindex'=>false,
        ],
]);

echo "<div id='modalContent'><div style='text-align:center'><img src='/images/loading.gif'></div></div>";
Modal::end();

$this->title = 'Expenditures';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="expenditure-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php
            
            $gridColumns = [
                [
                    'class' => 'kartik\grid\SerialColumn',
                    //'contentOptions' => ['class' => 'kartik-sheet-style'],
                    'width' => '20px',
                    'header' => '',
                    'headerOptions' => ['class' => 'kartik-sheet-style'],
                    //'mergeHeader' => true,
                ],
                [
                    'attribute'=>'expenditure_class_id',
                    //'header'=>'Category',
                    //'width'=>'100px',
                    'value'=>function ($model, $key, $index, $widget) { 
                            return $model->expenditureClass->name;
                        },
                    //'headerOptions' => ['style' => 'text-decoration: underline;'],
                    'contentOptions' => ['style' => 'font-variant:small-caps; text-align: left; font-weight: bold; text-decoration: underline; font-size: large;', ],
                
                    'group'=>true,  // enable grouping,
                    'groupedRow'=>true,                    // move grouped column to a single grouped row
                    //'groupOddCssClass'=>'kv-grouped-row',  // configure odd group cell css class
                    //'groupEvenCssClass'=>'kv-grouped-row', // configure even group cell css class
                ],
                [
                    'attribute'=>'expenditure_subclass_id',
                    //'header'=>'Category',
                    //'width'=>'100px',
                    'value'=>function ($model, $key, $index, $widget) { 
                            return $model->expenditureSubclass->name;
                        },
                    //'headerOptions' => ['style' => 'text-align: left;'],
                    'contentOptions' => ['style' => 'text-align: left; font-weight:bold; padding-left: 35px;'],
                
                    'group'=>true,  // enable grouping,
                    'groupedRow'=>true,                    // move grouped column to a single grouped row
                    //'groupOddCssClass'=>'kv-grouped-row',  // configure odd group cell css class
                    //'groupEvenCssClass'=>'kv-grouped-row', // configure even group cell css class
                    'groupFooter' => function ($model, $key, $index, $widget) { // Closure method
                        return [
                            'mergeColumns' => [[1,2]], // columns to merge in summary
                            'content' => [             // content to show in each summary cell
                                3 => 'TOTAL : '.$model->expenditureSubclass->name,
                                4 => GridView::F_SUM,
                            ],
                            'contentFormats' => [      // content reformatting for each summary cell
                                4 => ['format' => 'number', 'decimals' => 2],
                            ],
                            'contentOptions' => [      // content html attributes for each summary cell
                                //3 => ['style' => 'font-variant:small-caps'],
                                4 => ['style' => 'text-align:right'],

                            ],
                            // html attributes for group summary row
                            'options' => ['class' => 'info table-info', 'style' => 'font-weight:bold; text-align: right;']
                        ];
                    }
                    ],
                [
                    'attribute'=>'name', 
                    'header'=>'Object of Expenditures',
                    'width'=>'650px',
                    'value'=>function ($model, $key, $index, $widget) { 
                            return $model->name;
                        },
                    'headerOptions' => ['style' => 'text-align: center'],
                    'contentOptions' => ['style' => 'text-align: left'],
                    //'mergeHeader' => true,
                ],
                /*[
                    'attribute'=>'amount',
                    'header'=>'Amount',
                    'width'=>'100px',
                    'value'=>function ($model, $key, $index, $widget) { 
                            $fmt = Yii::$app->formatter;
                            return $fmt->asDecimal($model->amount);
                        },
                    'headerOptions' => ['style' => 'text-align: center'],
                    'contentOptions' => ['style' => 'text-align: right'],
                ],*/
                [
                    'class'=>'kartik\grid\EditableColumn',
                    'attribute'=>'amount',
                    'header'=>'Amount',
                    'refreshGrid'=>true,
                    //'readonly' => !$isMember,
                    'value'=>function ($model, $key, $index, $widget) { 
                            $fmt = Yii::$app->formatter;
                            return $fmt->asDecimal($model->amount);
                        },
                    'editableOptions'=> function ($model , $key , $index) {
                        return [
                            'options' => ['id' => $index . '_' . $model->expenditure_id],
                            'placement'=>'left',
                            //'disabled'=>($model->ppmp->status_id != Ppmp::STATUS_PENDING),
                            'name'=>'amount',
                            'asPopover' => true,
                            'value' => $model->amount,
                            'inputType' => \kartik\editable\Editable::INPUT_TEXT,
                            'formOptions'=>['action' => ['/budget/expenditure/updateamount']], // point to the new action
                        ];
                    },
                    'hAlign'=>'right',
                    'vAlign'=>'left',
                    'width'=>'100px',
                ],
                [
                    'attribute'=>'amount',
                    'header'=>'as per Budget Allocation',
                    'width'=>'100px',
                    'value'=>function ($model, $key, $index, $widget) { 
                            $fmt = Yii::$app->formatter;
                            return $fmt->asDecimal(0.00);
                        },
                    'headerOptions' => ['style' => 'text-align: center'],
                    'contentOptions' => ['style' => 'text-align: right'],
                ],
                [
                    'attribute'=>'amount',
                    'header'=>'% Budget Allocation',
                    'width'=>'100px',
                    'value'=>function ($model, $key, $index, $widget) { 
                            $fmt = Yii::$app->formatter;
                            return $fmt->asDecimal(0.00);
                        },
                    'headerOptions' => ['style' => 'text-align: center'],
                    'contentOptions' => ['style' => 'text-align: right'],
                ],
                [
                    'attribute'=>'amount',
                    'header'=>'Actual Expenditures',
                    'width'=>'100px',
                    'value'=>function ($model, $key, $index, $widget) { 
                            $fmt = Yii::$app->formatter;
                            return $fmt->asDecimal(0.00);
                        },
                    'headerOptions' => ['style' => 'text-align: center'],
                    'contentOptions' => ['style' => 'text-align: right'],
                ],
                [
                    'attribute'=>'amount',
                    'header'=>'% Actual Expenditures',
                    'width'=>'100px',
                    'value'=>function ($model, $key, $index, $widget) { 
                            $fmt = Yii::$app->formatter;
                            return $fmt->asDecimal(0.00);
                        },
                    'headerOptions' => ['style' => 'text-align: center'],
                    'contentOptions' => ['style' => 'text-align: right'],
                ],
            ];
            
            echo GridView::widget([
                'id' => 'expenditure-objects',
                'dataProvider' => $dataProvider,
                //'filterModel' => $searchModel,
                'columns' => $gridColumns, // check the configuration for grid columns by clicking button above
                'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
                'headerRowOptions' => ['class' => 'kartik-sheet-style'],
                'filterRowOptions' => ['class' => 'kartik-sheet-style'],
                'pjax' => true, // pjax is set to always true for this demo
                // set left panel buttons
                'panel' => [
                    'heading'=>'<h3 class="panel-title">BUDGET ESTIMATE per NATIONAL EXPENDITURE PROGRAM</h3>',
                    'type' => GridView::TYPE_PRIMARY,
                    'before'=>Html::button('Add Expenditure', ['value' => Url::to(['expenditure/addexpenditures']), 'title' => 'Expenditure', 'class' => 'btn btn-info', 'style'=>'margin-right: 6px;', 'id'=>'buttonAddExpenditure']),
                    'after'=>false,
                ],
                // set right toolbar buttons
                'toolbar' => 
                                [
                                    [
                                        'content'=>'',
                                            /*Html::button('Submit PPMP', ['value' => Url::to(['ppmp/submit', 'id'=>$model->ppmp_id]), 'title' => 'Submit PPMP', 'class' => 'btn btn-info', 'style'=>'margin-right: 6px; display: "";', 'id'=>'buttonSubmitPpmp']).*/
                                            /*Html::button('Add Item  <i class="glyphicon glyphicon-list"></i>', ['disabled' => $disableAddItem OR !$isMember, 'value' => Url::to(['ppmpitem/additems', 'id'=>$model->ppmp_id, 'year'=>$model->year]), 'title' => 'PPMP Item', 'class' => 'btn btn-success', 'style'=>'margin-right: 6px; display: "";', 'id'=>'buttonAddPpmpItem'])*/
                                    ],
                                ],
                // set export properties
                'export' => [
                    'fontAwesome' => true
                ],
                'persistResize' => false,
                'toggleDataOptions' => ['minCount' => 10],
                //'exportConfig' => $exportConfig,
                //'itemLabelSingle' => 'item',
                //'itemLabelPlural' => 'items'
                
                'showPageSummary' => true,
                'pageSummaryRowOptions' => ['class' => 'kv-page-summary warning'],
            ]);
    
        ?>
</div>
