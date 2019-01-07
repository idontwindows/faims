<?php
use kartik\grid\GridView;
use yii\data\ArrayDataProvider;

    $dataProvider = new ArrayDataProvider([
        'allModels' => $model->inventoryWithdrawaldetails,
        'key' => 'inventory_withdrawaldetails_id'
    ]);
    $gridColumns = [
        ['class' => 'yii\grid\SerialColumn'],
        'inventory_withdrawaldetails_id',
        [
                'attribute' => 'inventoryWithdrawal.inventory_withdrawal_id',
                'label' => 'Inventory Withdrawal'
            ],
        'quantity',
        'price',
        'withdarawal_status_id',
        [
            'class' => 'yii\grid\ActionColumn',
            'controller' => 'inventory-withdrawaldetails'
        ],
    ];
    
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => $gridColumns,
        'containerOptions' => ['style' => 'overflow: auto'],
        'pjax' => true,
        'beforeHeader' => [
            [
                'options' => ['class' => 'skip-export']
            ]
        ],
        'export' => [
            'fontAwesome' => true
        ],
        'bordered' => true,
        'striped' => true,
        'condensed' => true,
        'responsive' => true,
        'hover' => true,
        'showPageSummary' => false,
        'persistResize' => false,
    ]);
