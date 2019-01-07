<?php
use kartik\grid\GridView;
use yii\data\ArrayDataProvider;

    $dataProvider = new ArrayDataProvider([
        'allModels' => $model->inventoryEntries,
        'key' => 'inventory_transactions_id'
    ]);
    $gridColumns = [
        ['class' => 'yii\grid\SerialColumn'],
        'inventory_transactions_id',
        [
                'attribute' => 'transactionType.transactiontype_id',
                'label' => 'Transaction Type'
            ],
        'rstl_id',
        [
                'attribute' => 'suppliers.suppliers_id',
                'label' => 'Suppliers'
            ],
        'po_number',
        'quantity',
        'amount',
        'total_amount',
        [
            'class' => 'yii\grid\ActionColumn',
            'controller' => 'inventory-entries'
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
