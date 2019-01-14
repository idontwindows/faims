<?php

use yii\helpers\Html;
use kartik\grid\GridView;

use yii\helpers\Url;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $searchModel common\models\procurement\ObligationrequestSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


$BaseURL = $GLOBALS['frontend_base_uri'];
$this->registerJsFile($BaseURL.'js/procurement/obligationrequest/ajax-modal-popup.js');


$this->title = 'Obligation Request Module';
$this->params['breadcrumbs'][] = $this->title;
?>



<div class="obligationrequest-index">


    <?php
    //Modal
    Modal::begin([
        'header' => '<h4 id="modalHeader" style="color: #ffffff"></h4>',
        'id' => 'modalObligation',
        'size' => 'modal-lg',
        'options'=> [
             'tabindex'=>false,
        ]
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
            'width'=>'13%',
        ],

        [

            'attribute'=>'payee',
            'label'=>'Payee',
            'headerOptions' => ['class' => 'kartik-sheet-style'],
            'width'=>'13%',
        ],

        [

            'attribute'=>'amount',
            'label'=>'Amount',
            'headerOptions' => ['class' => 'kartik-sheet-style'],
            'contentOptions' => [
                'style'=>'text-align:right;'
            ],
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
            'class' => '\kartik\grid\ActionColumn',
            'width'=>'5%',
            'buttons'=>[
            'update' => function($url,$model,$key){
            $btn = Html::button('<span class=\'glyphicon glyphicon-pencil\'></span>', ['value' => Url::to(['update?id='.$model["obligation_request_id"].'&view=edit']), 'title' => 'Edit Obligation Request', 'tab-index'=>0 , 'class' => 'btn btn-success', 'style'=>'margin-right: 6px;', 'id'=>'buttonAddObligation']);
            return $btn;
            },
            'view' => function($url,$model,$key){
            $btn = Html::button('<span class=\'glyphicon glyphicon-eye-open\'></span>', ['value' => Url::to(['view?id='.$model["obligation_request_id"]]), 'title' => 'View Obligation Request', 'tab-index'=>0 , 'class' => 'btn btn-info', 'style'=>'margin-right: 6px;', 'id'=>'buttonAddObligation']);
            return $btn;
            },
            'delete'=>function($url, $model){
                return Html::a("<span class='glyphicon glyphicon-trash'></span>", $url, [
                "title"=>"Delete",
                 "aria-label"=>"Delete",
                 "data-pjax"=>"1",
                 "data-method"=>"post",
                 "data-confirm"=>"Are you sure you want to delete?",
                 "class"=>"btn btn-danger"
                  ]);
            },
        ],
            'headerOptions' => ['class' => 'kartik-sheet-style'],
            'deleteOptions' => ['label' => '<span class="glyphicon glyphicon-remove"></span>']
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
                        Html::button('Create Obligation Request', ['value' => Url::to(['obligationrequest/create']), 'title' => 'Create Obligation Request', 'tab-index'=>0 , 'class' => 'btn btn-success', 'style'=>'margin-right: 6px;', 'id'=>'buttonAddObligation']) .
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
