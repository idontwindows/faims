<?php

use yii\helpers\Html;
use kartik\grid\GridView;

use yii\widgets\ActiveForm;
use common\models\procurementplan\Ppmp;
use yii\helpers\ArrayHelper;
use kartik\editable\Editable;



/* @var $this yii\web\View */
/* @var $searchModel common\models\procurementplan\AppSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Annual Procurement Plan';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ppmpitem-index">

    <?php $selectyear = $this->render('_selectyear', ['model' => $searchModel]); ?>

    <?php

    $columns = [
               [
                    'class' => 'kartik\grid\SerialColumn',
                    'contentOptions' => ['class' => 'kartik-sheet-style'],
                    'width' => '20px',
                    'header' => '#',
                    'headerOptions' => [
                        'class' => 'kartik-sheet-style',
                        'style' => 'text-align: left; background-color: #7e9fda;'
                    ],
                    //'mergeHeader' => true,
                ],
                [
                    'attribute'=>'availability',
                    'header'=>'Category',
                    'visible' =>  $dataProvider->totalCount > 0 ? true : false,
                    'value'=>function ($model, $key, $index, $widget) { 
                            if($model->availability == 1){
                                return 'PART I. AVAILABLE AT PROCUREMENT SERVICE STORES';
                            }elseif($model->availability == 2){
                                return 'PART II. OTHER ITEMS NOT AVAILABLE AT PS BUT REGULARLY PURCHASED FROM OTHER SOURCES (Note: Please indicate price of items)';
                            }
                        },
                    'headerOptions' => ['style' => 'background-color: #fee082;'],
                    'contentOptions'=>['style'=>'background-color: #fee082; font-weight: bold;'],
                
                    'group'=>true,  // enable grouping,
                    'groupedRow'=>true,                    // move grouped column to a single grouped row
                    //'contentOptions' => ['style' => 'text-align: left; background-color: #ffe699;'],
                    
                    'groupOddCssClass'=>'',  // configure odd group cell css class
                    'groupEvenCssClass'=>'', // configure even group cell css class
                ],
                [
                    'attribute'=>'item_category_id',
                    'header'=>'Category',
                    'visible' =>  $dataProvider->totalCount > 0 ? true : false,
                    'width'=>'100px',
                    'value'=>function ($model, $key, $index, $widget) { 
                            return $model->itemcategory->category_name;
                        },
                    'headerOptions' => ['style' => 'text-align: left; background-color: #7e9fda;'],
                    'contentOptions' => ['style' => 'text-align: left; background-color: #7e9fda;'],
                
                    'group'=>true,  // enable grouping,
                    'groupedRow'=>true,                    // move grouped column to a single grouped row
                    'groupOddCssClass'=>'',  // configure odd group cell css class
                    'groupEvenCssClass'=>'', // configure even group cell css class
                ],
                [
                    'attribute'=>'description', 
                    'header'=>'Items & Specification',
                    'width'=>'650px',
                    'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78; color: black'],
                    'contentOptions' => ['style' => 'text-align: left;'],
                    //'mergeHeader' => true,
                ],
                [
                    'attribute'=>'unit', 
                    'header'=>'Unit of Measure',
                    'value'=>function ($model, $key, $index, $widget) { 
                            return $model->unitofmeasure->name;
                        },
                    'width'=>'100px',
                    'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78; color: black'],
                    'contentOptions' => ['style' => 'text-align: center'], 
                    //'mergeHeader' => true,
                ],
                [
                    'attribute'=>'jan',
                    'header'=>'Jan',
                    'width'=>'100px',
                    'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78; color: black'],
                    'contentOptions' => ['style' => 'text-align: right'],
                ],
                [
                    'attribute'=>'feb',
                    'header'=>'Feb',
                    'width'=>'75px',
                    'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78; color: black'],
                    'contentOptions' => ['style' => 'text-align: right'],
                ],
                [
                    'attribute'=>'mar',
                    'header'=>'Mar',
                    'width'=>'75px',
                    'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78; color: black'],
                    'contentOptions' => ['style' => 'text-align: right'],
                ],
                [
                    'attribute'=>'q1',
                    'header'=>'Q1',
                    'width'=>'75px',
                    'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78; color: black'],
                    'contentOptions' => ['style' => 'text-align: right; background-color: #ededed'],
                ],
                [
                    'attribute'=>'q1amount',
                    'header'=>'Q1 AMOUNT',
                    'width'=>'75px',
                    'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78; color: black'],
                    'contentOptions' => ['style' => 'text-align: right; background-color: #ededed; font-weight: bold;'],
                ],
                [
                    'attribute'=>'apr',
                    'header'=>'Apr',
                    'width'=>'75px',
                    'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78; color: black'],
                    'contentOptions' => ['style' => 'text-align: right'],
                ],
                [
                    'attribute'=>'may',
                    'header'=>'May',
                    'width'=>'75px',
                    'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78; color: black'],
                    'contentOptions' => ['style' => 'text-align: right'],
                ],
                [
                    'attribute'=>'jun',
                    'header'=>'Jun',
                    'width'=>'75px',
                    'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78; color: black'],
                    'contentOptions' => ['style' => 'text-align: right'],
                ],
                [
                    'attribute'=>'q2',
                    'header'=>'Q2',
                    'width'=>'75px',
                    'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78; color: black'],
                    'contentOptions' => ['style' => 'text-align: right; background-color: #ededed'],
                ],
                [
                    'attribute'=>'q2amount',
                    'header'=>'Q2 AMOUNT',
                    'width'=>'75px',
                    'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78; color: black'],
                    'contentOptions' => ['style' => 'text-align: right; background-color: #ededed; font-weight: bold;'],
                ],
                [
                    'attribute'=>'jul',
                    'header'=>'Jul',
                    'width'=>'75px',
                    'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78; color: black'],
                    'contentOptions' => ['style' => 'text-align: right'],
                ],
                [
                    'attribute'=>'aug',
                    'header'=>'Aug',
                    'width'=>'75px',
                    'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78; color: black'],
                    'contentOptions' => ['style' => 'text-align: right'],
                ],
                [
                    'attribute'=>'sep',
                    'header'=>'Sep',
                    'width'=>'75px',
                    'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78; color: black'],
                    'contentOptions' => ['style' => 'text-align: right'],
                ],
                [
                    'attribute'=>'q3',
                    'header'=>'Q3',
                    'width'=>'75px',
                    'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78; color: black'],
                    'contentOptions' => ['style' => 'text-align: right; background-color: #ededed'],
                ],
                [
                    'attribute'=>'q3amount',
                    'header'=>'Q3 AMOUNT',
                    'width'=>'75px',
                    'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78; color: black'],
                    'contentOptions' => ['style' => 'text-align: right; background-color: #ededed; font-weight: bold;'],
                ],
                [
                    'attribute'=>'oct',
                    'header'=>'Oct',
                    'width'=>'75px',
                    'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78; color: black'],
                    'contentOptions' => ['style' => 'text-align: right'],
                ],
                [
                    'attribute'=>'nov',
                    'header'=>'Nov',
                    'width'=>'75px',
                    'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78; color: black'],
                    'contentOptions' => ['style' => 'text-align: right'],
                ],
                [
                    'attribute'=>'dec',
                    'header'=>'Dec',
                    'width'=>'75px',
                    'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78; color: black'],
                    'contentOptions' => ['style' => 'text-align: right'],
                ],
                [
                    'attribute'=>'q4',
                    'header'=>'Q4',
                    'width'=>'75px',
                    'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78; color: black'],
                    'contentOptions' => ['style' => 'text-align: right; background-color: #ededed'],
                ],
                [
                    'attribute'=>'q4amount',
                    'header'=>'Q4 AMOUNT',
                    'width'=>'75px',
                    'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78; color: black'],
                    'contentOptions' => ['style' => 'text-align: right; background-color: #ededed; font-weight: bold;'],
                ],
                [
                    'attribute'=>'quantity',
                    'header'=>'Total Quantity for the year',
                    'width'=>'75px',
                    'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78; color: black'],
                    'contentOptions' => ['style' => 'text-align: center;'],
                ],
                [
                    'attribute'=>'cost',
                    'header'=>'Price Catalogue',
                    'width'=>'100px',
                    'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78; color: black'],
                    'contentOptions' => ['style' => 'text-align: right'],
                    'pageSummary' => 'TOTAL'
                ],
                [
                    'attribute'=>'totalamount',
                    'header'=>'Total Amount for the year',
                    'width'=>'75px',
                    'mergeHeader' => true,
                    'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78; color: black'],
                    'contentOptions' => ['style' => 'text-align: right; background-color: #ededed; font-weight: bold;'],
                    'format' => ['decimal', 2],
                    'pageSummary' => true
                ],
            ];

    ?>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'showPageSummary' => true,
        'columns' => $columns,
                'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
                'headerRowOptions' => ['class' => 'kartik-sheet-style'],
                'filterRowOptions' => ['class' => 'kartik-sheet-style'],
                'pjax' => true, // pjax is set to always true for this demo
                // set left panel buttons
                'panel' => [
                    'heading'=>'<h3 class="panel-title">Annual Procurement Plan</h3>',
                    'type'=>'primary',
                    
                ],
                'toolbar' => [
                    'content' => $selectyear,
            ],
                 'export' => [
                    'fontAwesome' => true
                ],
                'persistResize' => false,
                'toggleDataOptions' => ['minCount' => 10],
                //'exportConfig' => $exportConfig,
                'itemLabelSingle' => 'item',
                'itemLabelPlural' => 'items'
    ]); ?>


        

</div>
