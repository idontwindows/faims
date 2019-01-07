<?php

use yii\helpers\Html;
use kartik\grid\GridView;

use yii\helpers\Url;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $searchModel common\models\procurement\ObligationrequestSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Obligation Request Module';
$this->params['breadcrumbs'][] = $this->title;
?>



<div class="obligationrequest-index">


    <?php
    // echo $this->render('_search', ['model' => $searchModel]);
    // Modal Obligation
    Modal::begin([
        'header' => '<h4 id="modalHeader" style="color: #ffffff"></h4>',
        //'headerOptions' => ['id' => 'modalHeader'],
        'id' => 'modalObligation',
        'size' => 'modal-lg',
    ]);
    echo "<div id='modalContent'><div style='text-align:center'><img src='/images/loading.gif'></div></div>";
    Modal::end();
    ?>


<?php

    $colorPluginOptions =  [
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
    ];

    $gridColumns = [

        [
            'class' => 'kartik\grid\SerialColumn',
            'contentOptions' => ['class' => 'kartik-sheet-style'],
            'width' => '5%',
            'vAlign' => 'top',
            'header' => '',
            'headerOptions' => ['class' => 'kartik-sheet-style'],
        ],

        [

            'attribute'=>'os_no',
            'label'=>'OS Number',
            'headerOptions' => ['class' => 'kartik-sheet-style'],
            //'group'=>true,  // enable grouping
            //'subGroupOf'=>1, // supplier column index is the parent group
            'width'=>'13%',
        ],

        [

            'attribute'=>'payee',
            'label'=>'Payee',
            'headerOptions' => ['class' => 'kartik-sheet-style'],
            //'group'=>true,  // enable grouping
            //'subGroupOf'=>1, // supplier column index is the parent group
            'width'=>'13%',
        ],

        [

            'attribute'=>'amount',
            'label'=>'Amount',
            'headerOptions' => ['class' => 'kartik-sheet-style'],
            'contentOptions' => [
                'style'=>'text-align:right;'
            ],
            //'group'=>true,  // enable grouping
            //'subGroupOf'=>1, // supplier column index is the parent group
            'width'=>'3%',
        ],


        [
            'attribute'=>'particulars',
            'label'=>'Particulars',
            'vAlign' => 'top',
            'format'=>'raw',
            'contentOptions' => [
                'style'=>'max-width:200px; overflow: auto; white-space: normal; word-wrap: break-word;'
            ],
            'width'=>'37%',
            'headerOptions' => ['class' => 'kartik-sheet-style'],
        ],


                [
                    'width'=>'5%',
            'class'
            => '\kartik\grid\ActionColumn',
                    'headerOptions' => ['class' => 'kartik-sheet-style'],
            'deleteOptions' => ['label' => '<i class="glyphicon glyphicon-remove"></i>']
        ],



    ];

    echo GridView::widget([
        'id' => 'kv-grid-data',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'pjax' => true,
        'columns' => $gridColumns,
        'pjaxSettings' => [
            'neverTimeout'=>false,
            'options' => [
                'enablePushState' => false,
            ],
        ],

        'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
        'headerRowOptions' => ['class' => 'kartik-sheet-style'],
        'filterRowOptions' => ['class' => 'kartik-sheet-style'],

        'toolbar' =>
            [
                [
                    'content'=>
                        Html::button('Create Obligation Request', ['value' => Url::to(['obligationrequest/create']), 'title' => 'Expenditure Class', 'class' => 'btn btn-success', 'style'=>'margin-right: 6px;', 'id'=>'buttonAddObligation']) .
                        Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['index'], ['data-pjax' => false, 'class' => 'btn btn-default', 'title'=>'Reset Grid'])
                ],
                '{export}',
                '{toggleData}'
            ],
        // set export properties
        'export' => [
            'fontAwesome' => true
        ],
        'bordered' => true,
        'striped' => true,
        'condensed' => true,
        'responsive' => true,
        'hover' => true,
        'showPageSummary' => true,
        'panel' => [
            'heading' => 'Data Details',
        ],
        'persistResize' => false,
        'toggleDataOptions' => ['minCount' => 10],
        'exportConfig' => true,
    ]);
    ?>







</div>
