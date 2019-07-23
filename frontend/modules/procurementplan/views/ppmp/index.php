<?php
use kartik\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

use kartik\datecontrol\DateControl;
use kartik\detail\DetailView;
use kartik\editable\Editable; 
use kartik\grid\GridView;

use yii\bootstrap\Modal;

//use common\models\procurement\Division;
/* @var $this yii\web\View */
/* @var $searchModel common\models\procurementplan\PpmpSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Ppmps';
$this->params['breadcrumbs'][] = $this->title;

// Modal LIB
Modal::begin([
    'header' => '<h4 id="modalHeader" style="color: #ffffff"></h4>',
    'id' => 'modalPpmp',
    'size' => 'modal-md',
]);

echo "<div id='modalContent'><div style='text-align:center'><img src='/images/loading.gif'></div></div>";
Modal::end();
?>
<div class="ppmp-index">

    <h3 style="text-align: center"><?= Html::encode('PROJECT PROCUREMENT MANAGEMENT PLAN (PPMP)') ?></h3>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <!--?= Html::a('Create Ppmp', ['create'], ['class' => 'btn btn-success']) ?-->
    </p>

        <?php
        echo GridView::widget([
            'id' => 'ppmp',
            
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                                        /*[
                                            'class' => 'kartik\grid\CheckboxColumn',
                                            'headerOptions' => ['class' => 'kartik-sheet-style'],
                                            'checkboxOptions' => function($model, $key, $index, $column){
                                                 $bool = Lineitembudgetobject::find()->where(['line_item_budget_id' => $_GET['id'], 'expenditure_object_id' => $model->expenditure_object_id])->count();
                                                 return ['checked' => $bool];
                                             }
                                        ],
                                        [
                                            'attribute'=>'expenditureSubClass.expenditureClass.expenditure_class_id', 
                                            'width'=>'250px',
                                            'value'=>function ($model, $key, $index, $widget) { 
                                                return $model->expenditureSubClass->expenditureClass->name;
                                            },
                                            'group'=>true,  // enable grouping,
                                            'groupedRow'=>true,                    // move grouped column to a single grouped row
                                            'groupOddCssClass'=>'kv-grouped-row',  // configure odd group cell css class
                                            'groupEvenCssClass'=>'kv-grouped-row', // configure even group cell css class
                                        ],
                                        [
                                            'attribute'=>'expenditure_sub_class_id', 
                                            'width'=>'250px',
                                            'value'=>function ($model, $key, $index, $widget) { 
                                                return $model->expenditureSubClass->name;
                                            },
                                            'group'=>true,  // enable grouping,
                                            'groupedRow'=>true,                    // move grouped column to a single grouped row
                                            //'groupOddCssClass'=>'kv-grouped-row',  // configure odd group cell css class
                                            //'groupEvenCssClass'=>'kv-grouped-row', // configure even group cell css class
                                        ],*/
                                        [
                                            'class' => 'kartik\grid\RadioColumn',
                                            'width' => '36px',
                                            'headerOptions' => ['class' => 'kartik-sheet-style'],
                                        ],
                                        //'ppmp_id',
                                        //'division_id',
                                        [
                                            //'attribute' => 'unit_id', 
                                            'vAlign' => 'middle',
                                            'width' => '180px',
                                            'value' => function ($model, $key, $index, $widget) { 
                                                return Html::a($model->division->name);
                                            },
                                            'filterType' => GridView::FILTER_SELECT2,
                                            'filter' => $listDivisions, 
                                            'filterWidgetOptions' => [
                                                'pluginOptions' => ['allowClear' => true],
                                                'options' => ['multiple' => false]
                                            ],
                                            'filterInputOptions' => ['placeholder' => 'Select Division'],
                                            'format' => 'raw'
                                        ],
                                        [
                                            //'attribute' => 'division_id', 
                                            'vAlign' => 'middle',
                                            'width' => '180px',
                                            'value' => function ($model, $key, $index, $widget) { 
                                                return Html::a($model->unit->name);
                                            },
                                            'filterType' => GridView::FILTER_SELECT2,
                                            'filter' => $listUnits, 
                                            'filterWidgetOptions' => [
                                                'pluginOptions' => ['allowClear' => true],
                                                'options' => ['multiple' => false]
                                            ],
                                            'filterInputOptions' => ['placeholder' => 'Select Unit'],
                                            'format' => 'raw'
                                        ],
                                        //'unit_id',
                                        'charged_to',
                                        'project_id',
                                        'year',
                                        [
                                            'class' => 'kartik\grid\RadioColumn',
                                            'headerOptions' => ['class' => 'kartik-sheet-style'],
                                        ],
                            ],
            'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
            'headerRowOptions' => ['class' => 'kartik-sheet-style'],
            'filterRowOptions' => ['class' => 'kartik-sheet-style'],
            'pjax' => true, // pjax is set to always true for this demo
            'panel' => [
                'type' => GridView::TYPE_PRIMARY,
                //'heading' => $heading,
            ],
            // set your toolbar
            'toolbar' => 
                        [
                            [
                                'content'=>
                                    Html::button('New PPMP', ['value' => Url::to(['ppmp/create']), 'title' => 'PPMP', 'class' => 'btn btn-success', 'style'=>'margin-right: 6px;', 'id'=>'buttonAddPpmp'])// .
                                    //Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['index'], ['data-pjax' => true, 'class' => 'btn btn-default', 'title'=>'Reset Grid'])
                            ],
                            //'{export}',
                            //'{toggleData}'
                        ],
            /*'toolbar' =>  [
                ['content' => 
                    Html::button('<i class="glyphicon glyphicon-plus"></i>', ['type' => 'button', 'title' =>'Add Items', 'class' => 'btn btn-success', 'onclick' => 'alert("This will launch the book creation form.\n\nDisabled for this demo!");']) . ' '.
                    Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['grid-demo'], ['data-pjax' => 0, 'class' => 'btn btn-default', 'title' => 'Reset Grid'])
                ],
                '{export}',
                '{toggleData}',
            ],*/
            // set export properties
            // parameters from the demo form
            //'bordered' => $bordered,
            //'striped' => $striped,
            //'condensed' => $condensed,
            //'responsive' => $responsive,
            //'hover' => $hover,
            //'showPageSummary' => $pageSummary,
            
            //'persistResize' => false,
            'toggleDataOptions' => ['minCount' => 10],
            //'exportConfig' => $exportConfig,
            'itemLabelSingle' => 'item',
            'itemLabelPlural' => 'items'
        ]);
        
        ?>
    
    <?php
            /*$colorPluginOptions =  [
            'showPalette' => true,
            'showPaletteOnly' => true,
            'showSelectionPalette' => true,
            'showAlpha' => false,
            'allowEmpty' => false,
            'preferredFormat' => 'name',
            'palette' => [
                [
                    "white", "black", "grey", "silver", "gold", "brown", 
                ],
                [
                    "red", "orange", "yellow", "indigo", "maroon", "pink"
                ],
                [
                    "blue", "green", "violet", "cyan", "magenta", "purple", 
                ],
            ]
        ];*/
        $gridColumns = [
        [
            'class' => 'kartik\grid\SerialColumn',
            'contentOptions' => ['class' => 'kartik-sheet-style'],
            'width' => '36px',
            'header' => '',
            'headerOptions' => ['class' => 'kartik-sheet-style'],
            'mergeHeader' => true,
        ],
        /*[
            'class' => 'kartik\grid\RadioColumn',
            'width' => '36px',
            'headerOptions' => ['class' => 'kartik-sheet-style'],
        ],
        [
            'attribute'=>'code',
            'header'=>'CODE',
            'width'=>'75px',
            'contentOptions' => ['style' => 'text-align: center'],
            'mergeHeader' => true,
        ],
            [
            'attribute'=>'description', 
            'header'=>'GENERAL DESCRIPTION',
            'width'=>'75px',
            'contentOptions' => ['style' => 'text-align: center'],
                'mergeHeader' => true,
        ],
            [
            'attribute'=>'quantity', 
            'header'=>'QUANTITY',
            'width'=>'75px',
            'contentOptions' => ['style' => 'text-align: center'], 
            'mergeHeader' => true,
        ],
                [
            'attribute'=>'unit', 
            'header'=>'UNIT',
            'width'=>'75px',
            'contentOptions' => ['style' => 'text-align: center'], 
            'mergeHeader' => true,
        ],
        [
            //'class' => 'kartik\grid\FormulaColumn',
            'header' => 'ESTIMATED BUDGET',
            'value' => function ($model, $key, $index, $widget) { 
                //$p = compact('model', 'key', 'index');
                //return $widget->col(4, $p) * $widget->col(5, $p);
                return 'hhahaha';
            },
            'mergeHeader' => true,
            'width' => '150px',
            'hAlign' => 'right',
            //'format' => ['decimal', 2],
            //'pageSummary' => true
        ],
        [
            //'attribute'=>'estimated_budget', 
            'header'=>'<CENTER>SCHEDULE / MILESTONE OF ACTIVITIES</CENTER><tr><th>JAN</th><th>FEB</th><th>MAR</th><th>APR</th><th>MAY</th><th>JUN</th><th>JUL</th><th>AUG</th><th>SEP</th><th>OCT</th><th>NOV</th><th>DEC</th></tr>',
            'contentOptions' => ['style' => 'text-align: center'],
            'headerOptions' => [
                'colspan' => 12,
            ]
        ],
        [
            'class' => 'kartik\grid\EditableColumn',
            'attribute' => 'q1',
            //'header' => 'JAN',
//            'readonly' => function($model, $key, $index, $widget) {
//                return (!$model->status); // do not allow editing of inactive records
//            },
            'editableOptions' => [
                //'header' => 'JAN', 
                'inputType' => \kartik\editable\Editable::INPUT_SPIN,
                'options' => [
                    'pluginOptions' => ['min' => 0, 'max' => 5000]
                ]
            ],
            'hAlign' => 'middle', 
            'vAlign' => 'middle',
            'width' => '7%',
            'format' => ['decimal', 0],
            'headerOptions' => [
                'style' => 'display: none;',
            ]
        ],
        [
            'class' => 'kartik\grid\EditableColumn',
            'attribute' => 'q2',
            'header' => 'FEB',
            'editableOptions' => [
                'header' => 'FEB', 
                'inputType' => \kartik\editable\Editable::INPUT_SPIN,
                'options' => [
                    'pluginOptions' => ['min' => 0, 'max' => 5000]
                ]
            ],
            'hAlign' => 'middle', 
            'vAlign' => 'middle',
            'width' => '7%',
            'format' => ['decimal', 0],
            'headerOptions' => [
                'style' => 'display: none;',
            ]
        ],
        [
            'class' => 'kartik\grid\EditableColumn',
            'attribute' => 'q3',
            'header' => 'MAR',
 
            'editableOptions' => [
                'header' => 'MAR', 
                'inputType' => \kartik\editable\Editable::INPUT_SPIN,
                'options' => [
                    'pluginOptions' => ['min' => 0, 'max' => 5000]
                ]
            ],
            'hAlign' => 'middle', 
            'vAlign' => 'middle',
            'width' => '7%',
            'format' => ['decimal', 0],
            'headerOptions' => [
                'style' => 'display: none;',
            ]
        ],
        [
            'class' => 'kartik\grid\EditableColumn',
            'attribute' => 'q4',
            'header' => 'APR',

            'editableOptions' => [
                'header' => 'APR', 
                'inputType' => \kartik\editable\Editable::INPUT_SPIN,
                'options' => [
                    'pluginOptions' => ['min' => 0, 'max' => 5000]
                ]
            ],
            'hAlign' => 'middle', 
            'vAlign' => 'middle',
            'width' => '7%',
            'format' => ['decimal', 0],
            'headerOptions' => [
                'style' => 'display: none;',
            ]
        ],
        [
            'class' => 'kartik\grid\EditableColumn',
            'attribute' => 'q5',
            'header' => 'MAY',

            'editableOptions' => [
                //'header' => 'MAY', 
                'inputType' => \kartik\editable\Editable::INPUT_SPIN,
                'options' => [
                    'pluginOptions' => ['min' => 0, 'max' => 5000]
                ]
            ],
            'hAlign' => 'middle', 
            'vAlign' => 'middle',
            'width' => '7%',
            'format' => ['decimal', 0],
            'headerOptions' => [
                'style' => 'display: none;',
            ]
        ],
        [
            'class' => 'kartik\grid\EditableColumn',
            'attribute' => 'q6',
            'header' => 'JUN',
            'editableOptions' => [
                'header' => 'JUN', 
                'inputType' => \kartik\editable\Editable::INPUT_SPIN,
                'options' => [
                    'pluginOptions' => ['min' => 0, 'max' => 5000]
                ]
            ],
            'hAlign' => 'middle', 
            'vAlign' => 'middle',
            'width' => '7%',
            'format' => ['decimal', 0],
            'headerOptions' => [
                'style' => 'display: none;',
            ]
        ],
        [
            'class' => 'kartik\grid\EditableColumn',
            'attribute' => 'q7',
            'header' => 'JUL',

            'editableOptions' => [
                'header' => 'JUL', 
                'inputType' => \kartik\editable\Editable::INPUT_SPIN,
                'options' => [
                    'pluginOptions' => ['min' => 0, 'max' => 5000]
                ]
            ],
            'hAlign' => 'middle', 
            'vAlign' => 'middle',
            'width' => '7%',
            'format' => ['decimal', 0],
            'headerOptions' => [
                'style' => 'display: none;',
            ]
        ],
        [
            'class' => 'kartik\grid\EditableColumn',
            'attribute' => 'q8',
            'header' => 'AUG',

            'editableOptions' => [
                'header' => 'AUG', 
                'inputType' => \kartik\editable\Editable::INPUT_SPIN,
                'options' => [
                    'pluginOptions' => ['min' => 0, 'max' => 5000]
                ]
            ],
            'hAlign' => 'middle', 
            'vAlign' => 'middle',
            'width' => '7%',
            'format' => ['decimal', 0],
            'headerOptions' => [
                'style' => 'display: none;',
            ]
        ],
        [
            'class' => 'kartik\grid\EditableColumn',
            'attribute' => 'q9',
            'header' => 'SEP',

            'editableOptions' => [
                'header' => 'SEP', 
                'inputType' => \kartik\editable\Editable::INPUT_SPIN,
                'options' => [
                    'pluginOptions' => ['min' => 0, 'max' => 5000]
                ]
            ],
            'hAlign' => 'middle', 
            'vAlign' => 'middle',
            'width' => '7%',
            'format' => ['decimal', 0],
            'headerOptions' => [
                'style' => 'display: none;',
            ]
        ],
        [
            'class' => 'kartik\grid\EditableColumn',
            'attribute' => 'q10',
            'header' => 'OCT',

            'editableOptions' => [
                'header' => 'OCT', 
                'inputType' => \kartik\editable\Editable::INPUT_SPIN,
                'options' => [
                    'pluginOptions' => ['min' => 0, 'max' => 5000]
                ]
            ],
            'hAlign' => 'middle', 
            'vAlign' => 'middle',
            'width' => '7%',
            'format' => ['decimal', 0],
            'headerOptions' => [
                'style' => 'display: none;',
            ]
        ],
        [
            'class' => 'kartik\grid\EditableColumn',
            'attribute' => 'q11',
            'header' => 'NOV',

            'editableOptions' => [
                'header' => 'NOV', 
                'inputType' => \kartik\editable\Editable::INPUT_SPIN,
                'options' => [
                    'pluginOptions' => ['min' => 0, 'max' => 5000]
                ]
            ],
            'hAlign' => 'middle', 
            'vAlign' => 'middle',
            'width' => '7%',
            'format' => ['decimal', 0],
            'headerOptions' => [
                'style' => 'display: none;',
            ]
        ],
        [
            'class' => 'kartik\grid\EditableColumn',
            'attribute' => 'q12',
            'header' => 'DEC',

            'editableOptions' => [
                'header' => 'DEC', 
                'inputType' => \kartik\editable\Editable::INPUT_SPIN,
                'options' => [
                    'pluginOptions' => ['min' => 0, 'max' => 5000]
                ]
            ],
            'hAlign' => 'middle', 
            'vAlign' => 'middle',
            'width' => '7%',
            'format' => ['decimal', 0],
            'headerOptions' => [
                'style' => 'display: none;',
            ]
        ],
        /*[
            'class' => 'kartik\grid\ExpandRowColumn',
            'width' => '50px',
            'value' => function ($model, $key, $index, $column) {
                return GridView::ROW_COLLAPSED;
            },
            'detail' => function ($model, $key, $index, $column) {
                return Yii::$app->controller->renderPartial('_expand-row-details', ['model' => $model]);
            },
            'headerOptions' => ['class' => 'kartik-sheet-style'],
            'expandOneOnly' => true
        ],*/
        /*[
            'class' => 'kartik\grid\EditableColumn',
            'attribute' => 'name',
            'pageSummary' => 'Total',
            'vAlign' => 'middle',
            'width' => '210px',
            'readonly' => function($model, $key, $index, $widget) {
                return (!$model->status); // do not allow editing of inactive records
            },
            'editableOptions' =>  function ($model, $key, $index) use ($colorPluginOptions) {
                return [
                    'header' => 'Name', 
                    'size' => 'md',
                    'afterInput' => function ($form, $widget) use ($model, $index, $colorPluginOptions) {
                        return $form->field($model, "color")->widget(\kartik\widgets\ColorInput::classname(), [
                            'showDefaultPalette' => false,
                            'options' => ['id' => "color-{$index}"],
                            'pluginOptions' => $colorPluginOptions,
                        ]);
                    }
                ];
            }
        ],*/

        ];
    echo GridView::widget([
        'id' => 'ppmp-items',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $gridColumns, // check the configuration for grid columns by clicking button above
        'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
        'headerRowOptions' => ['class' => 'kartik-sheet-style'],
        'filterRowOptions' => ['class' => 'kartik-sheet-style'],
        'pjax' => true, // pjax is set to always true for this demo
        // set your toolbar
        'toolbar' =>  [
            ['content' => 
                Html::button('<i class="glyphicon glyphicon-plus"></i>', ['type' => 'button', 'title' =>'Add Items', 'class' => 'btn btn-success', 'onclick' => 'alert("This will launch the book creation form.\n\nDisabled for this demo!");']) . ' '.
                Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['grid-demo'], ['data-pjax' => 0, 'class' => 'btn btn-default', 'title' => 'Reset Grid'])
            ],
            '{export}',
            '{toggleData}',
        ],
        // set export properties
        'export' => [
            'fontAwesome' => true
        ],
        // parameters from the demo form
        //'bordered' => $bordered,
        //'striped' => $striped,
        //'condensed' => $condensed,
        //'responsive' => $responsive,
        //'hover' => $hover,
        //'showPageSummary' => $pageSummary,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            //'heading' => $heading,
        ],
        'persistResize' => false,
        'toggleDataOptions' => ['minCount' => 10],
        //'exportConfig' => $exportConfig,
        'itemLabelSingle' => 'item',
        'itemLabelPlural' => 'items'
    ]);
    ?>
</div>
