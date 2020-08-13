<?php
use yii\helpers\Html;
use yii\helpers\Url;

use kartik\detail\DetailView;
use kartik\editable\Editable;
use kartik\grid\GridView;

use yii\bootstrap\Modal;

use common\models\procurementplan\Ppmp;
use common\models\cashier\Lddapada;
/* @var $this yii\web\View */
/* @var $model common\models\cashier\Lddapada */

$this->title = $model->batch_number;
$this->params['breadcrumbs'][] = ['label' => 'LDDAP-ADA', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

Modal::begin([
    'header' => '<h4 id="modalHeader" style="color: #ffffff"></h4>',
    'id' => 'modalCreditor',
    'size' => 'modal-lg',
    'options'=> [
             'tabindex'=>false,
        ],
]);

echo "<div id='modalContent'><div style='text-align:center'><img src='/images/loading.gif'></div></div>";
Modal::end();

Modal::begin([
    'header' => '<h4 id="modalHeader" style="color: #ffffff"></h4>',
    'id' => 'modalContainer',
    'size' => 'modal-sm',
    'options'=> [
             'tabindex'=>false,
        ],
]);

echo "<div id='modalContent'><div style='text-align:center'><img src='/images/loading.gif'></div></div>";
Modal::end();

Modal::begin([
    'header' => '<h4 id="modalHeader" style="color: #ffffff"></h4>',
    'id' => 'modalPreview',
    'size' => 'modal-lg',
    'options'=> [
             'tabindex'=>false,
        ],
]);

echo "<div id='modalContent'><div style='text-align:center'><img src='/images/loading.gif'></div></div>";
Modal::end();

echo $model->total;
?>
<div class="lddapada-view">

    <?php $attributes = [
            [
                'group'=>true,
                //'label'=>'<center>LDDAP-ADA</center>',
                'rowOptions'=>['class'=>'info'],
            ],
            [
                'columns' => [
                    [
                        'attribute'=>'batch_number',
                        'label' => 'Department',
                        'value'=>'Department of Science and Technology IX',
                        'valueColOptions'=>['style'=>'width:30%'],
                    ],
                    [
                        'attribute'=>'batch_number',
                        'format'=>'raw',
                        'value'=>'<kbd>'.$model->batch_number.'</kbd>',
                        'valueColOptions'=>['style'=>'width:30%; font-size:18px; font-weight: bold;'],
                    ],
                ],
            ],
            [
                'columns' => [
                    [
                        'attribute'=>'batch_number',
                        'value' => '',
                        'valueColOptions'=>['style'=>'width:30%'],
                        'label'=>'Entity Name',
                    ],
                    [
                        'attribute'=>'batch_date',
                        'value'=>date('m/d/Y', strtotime($model->batch_date)),
                        'valueColOptions'=>['style'=>'width:30%'],
                        'label'=>'Date',
                    ],
                ],
            ],
            [
                'columns' => [
                    [
                        'attribute'=>'batch_number',
                        'value' => '',
                        'valueColOptions'=>['style'=>'width:30%'],
                        'label'=>'Operating Unit',
                    ],
                    [
                        'attribute'=>'batch_number',
                        'value' => Lddapada::FUND_CLUSTER,
                        'valueColOptions'=>['style'=>'width:30%'],
                        'label'=>'Fund Cluster',
                    ],
                ],
            ],
            [
                'columns' => [
                    [
                        'attribute'=>'batch_number',
                        'value' => 'LBP Centro 2195-9000-54',
                        'valueColOptions'=>['style'=>'width:30%'],
                        'label'=>'MDS-GSB BRANCH / MDS SUB ACCOUNT NO.',
                        'inputContainer' => ['class'=>'col-sm-6'],
                    ],
                    [
                        'attribute'=>'batch_number',
                        'value' => '',
                        'valueColOptions'=>['style'=>'width:30%'],
                        'label'=>'',
                    ],
                ],
            ],
        ];?>
    <?= DetailView::widget([
            'model' => $model,
            'mode'=>DetailView::MODE_VIEW,
            /*'deleteOptions'=>[ // your ajax delete parameters
                'params' => ['id' => 1000, 'kvdelete'=>true],
            ],*/
            'container' => ['id'=>'kv-demo'],
            //'formOptions' => ['action' => Url::current(['#' => 'kv-demo'])] // your action to delete
            
            'buttons1' => '', //hides buttons on detail view
            'attributes' => $attributes,
            'condensed' => true,
            'responsive' => true,
            'hover' => true,
            'panel' => [
                //'type' => 'Primary', 
                'heading'=>'<center>LIST OF DUE AND DEMANDABLE ACCOUNTS PAYABLE - ADVICE TO DEBIT ACCOUNTS<BR/>(LDDAP-ADA)</center>',
                'type'=>DetailView::TYPE_PRIMARY,
                //'footer' => '<div class="text-center text-muted">This is a sample footer message for the detail view.</div>'
            ],
            
        ]); ?>

    <?php
        $gridColumns = [
                [
                    'class' => 'kartik\grid\SerialColumn',
                    'contentOptions' => ['class' => 'kartik-sheet-style'],
                    'width' => '20px',
                    'header' => '',
                    'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78'],
                    //'mergeHeader' => true,
                ],
                'name',
                'account_number',
                //'alobs_id',
                //'expenditure_object_id',
                'gross_amount',
                //'check_number',
                [
                    'class'=>'kartik\grid\EditableColumn',
                    'attribute'=>'alobs_id',
                    'header'=>'ROA/ALOBS NO.',
                    //'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78'],
                    'refreshGrid'=>true,
                    'editableOptions'=> function ($model , $key , $index) {
                        return [
                            'options' => ['id' => $index . '_' . $model->lddapada_id . 'alobs'],
                            'placement'=>'left',
                            'name'=>'alobs_id',
                            'asPopover' => true,
                            'inputType' => Editable::INPUT_DROPDOWN_LIST,
                            'data' => [0 => 'pass', 1 => 'fail', 2 => 'waived', 3 => 'todo'],
                            'formOptions'=>['action' => ['/cashier/lddapada/updatealobs']], // point to the new action
                        ];
                    },
                    'hAlign'=>'center',
                    'vAlign'=>'left',
                    'width'=>'100px',
                ],
                [
                    'class'=>'kartik\grid\EditableColumn',
                    'attribute'=>'check_number',
                    'header'=>'Check Number',
                    //'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78'],
                    'refreshGrid'=>true,
                    'editableOptions'=> function ($model , $key , $index) {
                        return [
                            'options' => ['id' => $index . '_' . $model->lddapada_id . 'checck'],
                            'placement'=>'left',
                            'name'=>'check_number',
                            'asPopover' => true,
                            'value' => $model->check_number,
                            'inputType' => \kartik\editable\Editable::INPUT_TEXT,
                            'formOptions'=>['action' => ['/cashier/lddapada/updatechecknumber']], // point to the new action
                        ];
                    },
                    'hAlign'=>'center',
                    'vAlign'=>'left',
                    'width'=>'100px',
                ],
            ];
    ?>
    <?= GridView::widget([
                'id' => 'lddap-ada-items',
                'dataProvider' => $lddapadaItemsDataProvider,
                //'filterModel' => $searchModel,
                'columns' => $gridColumns, // check the configuration for grid columns by clicking button above
                'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
                'headerRowOptions' => ['class' => 'kartik-sheet-style'],
                'filterRowOptions' => ['class' => 'kartik-sheet-style'],
                'pjax' => true, // pjax is set to always true for this demo
                // set left panel buttons
                'panel' => [
                    'heading'=>'<h3 class="panel-title">CREDITORS</h3>',
                    'type'=>'primary',
                ],
                // set right toolbar buttons
                'toolbar' => 
                                [
                                    [
                                        'content'=>
                                            /*Html::button('Add Items  <i class="glyphicon glyphicon-list"></i>', ['value' => Url::to(['lddapadaitem/additems', 'id'=>$model->lddapada_id]), 'title' => 'Items', 'class' => 'btn btn-success', 'style'=>'margin-right: 6px; display: "";', 'id'=>'buttonAddItems']) . */
                                        
                                            Html::button('Add Creditors  <i class="glyphicon glyphicon-plus"></i>', ['value' => Url::to(['lddapadaitem/addcreditors', 'id'=>$model->lddapada_id]), 'title' => 'Creditor', 'class' => 'btn btn-success', 'style'=>'margin-right: 6px; display: "";', 'id'=>'buttonAddCreditors']) .
                                        
                                            Html::button('Save  <i class="glyphicon glyphicon-floppy-save"></i>', ['value' => Url::to(['lddapada/save', 'id'=>$model->lddapada_id]), 'title' => 'LDDAPADA', 'class' => 'btn btn-info', 'style'=>'margin-right: 6px;'.(($model->saved === Lddapada::CHANGED) ? '' : 'display: none;'), 'id'=>'buttonSave']) .
                                        
                                            Html::button('Print  <i class="glyphicon glyphicon-print"></i>', ['value' => Url::to(['lddapada/preview', 'id'=>$model->lddapada_id]), 'title' => 'LDDAPADA', 'class' => 'btn btn-info', 'style'=>'margin-right: 6px;'.(($model->saved === Lddapada::SAVED) ? '' : 'display: none;'), 'id'=>'buttonPrintPreview']) 
                                        
                                            //$EnablePrint="<a href='/reports/preview?url=/lab/request/print-request?id=".$model->request_id."' class='btn btn-primary' style='margin-left: 5px'><i class='fa fa-print'></i> Print Request</a>";
                                        
                                        
                                    ],
                                ],
                // set export properties
                'export' => [
                    'fontAwesome' => true
                ],
                'persistResize' => false,
                'toggleDataOptions' => ['minCount' => 10],
                //'exportConfig' => $exportConfig,
                'itemLabelSingle' => 'item',
                'itemLabelPlural' => 'items'
            ]);
    
    ?>
</div>
