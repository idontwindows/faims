<?php
use kartik\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;

use kartik\datecontrol\DateControl;
use kartik\detail\DetailView;
use kartik\editable\Editable; 
use kartik\grid\GridView;

use yii\bootstrap\Modal;

use common\models\cashier\Creditor;
use common\models\finance\Request;
use common\models\finance\Requestdistrict;
use common\models\system\Profile;
use common\models\system\Usersection;
use common\models\sec\Blockchain;
/* @var $this yii\web\View */
/* @var $searchModel common\models\finance\RequestSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Request';
$this->params['breadcrumbs'][] = $this->title;

// Modal Create Request
Modal::begin([
    'header' => '<h4 id="modalHeader" style="color: #ffffff"></h4>',
    'id' => 'modalRequest',
    'size' => 'modal-md',
    'options'=> [
             'tabindex'=>false,
        ],
]);

echo "<div id='modalContent'><div style='text-align:center'><img src='/images/loading.gif'></div></div>";
Modal::end();

// Modal Create New Creditor
Modal::begin([
    'header' => '<h4 id="modalCreditorHeader" style="color: #ffffff"></h4>',
    'id' => 'modalCreditor',
    'size' => 'modal-md',
    'options'=> [
             'tabindex'=>false,
        ],
]);

echo "<div id='modalContent'><div style='text-align:center'><img src='/images/loading.gif'></div></div>";
Modal::end();
?>

<div class="request-index">

    <h1><?= Html::encode($this->title) ?></h1>
<?php Pjax::begin(); ?>
      <?php
        echo GridView::widget([
            'id' => 'request',
            'dataProvider' => $dataProvider,
            'columns' => [
                            [
                                'attribute'=>'request_number',
                                'headerOptions' => ['style' => 'text-align: center;'],
                                'contentOptions' => ['style' => 'vertical-align:middle; text-align: center; font-weight: bold;'],
                                'width'=>'120px',
                                'format'=>'raw',
                                'value'=>function ($model, $key, $index, $widget) { 
                                    return $model->request_number;
                                }
                            ],
                            [
                                'attribute'=>'request_date',
                                'headerOptions' => ['style' => 'text-align: center;'],
                                'contentOptions' => ['style' => 'vertical-align:middle; text-align: center;'],
                                'width'=>'120px',
                                'value'=>function ($model, $key, $index, $widget) { 
                                    return date('Y-m-d', strtotime($model->request_date));
                                },
                            ],
                            [
                                'attribute'=>'payee_id',
                                'headerOptions' => ['style' => 'text-align: center;'],
                                'contentOptions' => ['style' => 'padding-left: 25px; font-weigth: bold;'],
                                'width'=>'800px',
                                'contentOptions' => [
                                    'style'=>'max-width:300px; overflow: auto; white-space: normal; word-wrap: break-word;'
                                ],
                                'format' => 'raw',
                                'value'=>function ($model, $key, $index, $widget) { 
                                    return Html::tag('span', '<b>'.Creditor::findOne($model->payee_id)->name.'</b>', [
                                        'title'=>'Created by: '.Profile::find($model->created_by)->one()->fullname,
                                        //'data-toggle'=>'tooltip',
                                        //'data-content'=>Profile::find($model->created_by)->one()->fullname,
                                        //'data-toggle'=>'popover',
                                        'style'=>'text-decoration: underline; cursor:pointer;'
                                    ]).'<br>' .$model->particulars;
                                },
                            ],
                            [
                                'attribute'=>'amount',
                                'headerOptions' => ['style' => 'text-align: center;'],
                                'contentOptions' => ['style' => 'vertical-align: middle; text-align: right; padding-right: 20px; font-weight: bold;'],
                                'width'=>'200px',
                                'format'=>['decimal',2],
                                'value'=>function ($model, $key, $index, $widget) { 
                                    return $model->amount;
                                },
                            ],
                            [
                                'attribute'=>'status_id',
                                'headerOptions' => ['style' => 'text-align: center;'],
                                'contentOptions' => ['style' => 'text-align: center; vertical-align:middle;'],
                                'width'=>'250px',
                                'format'=>'raw',
                                'value'=>function ($model, $key, $index, $widget) { 
                                    return '<span class="label label-info">'.$model->status->name.'</span>';
                                },
                            ],
                            [
                                'attribute'=>'division_id',
                                'headerOptions' => ['style' => 'text-align: center;'],
                                'contentOptions' => ['style' => 'text-align: right; vertical-align:middle; padding-right: 25px; font-weight: bold;'],
                                'width'=>'100px',
                                'format'=>'raw',
                                'value'=>function ($model, $key, $index, $widget) { 
                                    return $model->division->name.'<br/>'.$model->profile->fullname;
                                },
                            ],
                            /*[
                                'attribute'=>'created_by',
                                'headerOptions' => ['style' => 'text-align: center;'],
                                'contentOptions' => ['style' => 'text-align: center; vertical-align:middle; '],
                                'width'=>'80px',
                                'value'=>function ($model, $key, $index, $widget) { 
                                    //return Profile::find($model->created_by)->one()->fullname;
                                    return $model->profile->fullname;
                                },
                            ],*/
                            [
                                'class'=>'kartik\grid\EditableColumn',
                                'attribute'=>'district_id',
                                'header'=>'District',
                                //'width'=>'350px',
                                'refreshGrid'=>true,
                                //'readonly' => !$isMember,
                                'value'=>function ($model, $key, $index, $widget) {
                                        if($model->district_id)
                                            return $model->district->name;
                                        else
                                            return '-';
                                    },
                                'editableOptions'=> function ($model , $key , $index) {
                                    return [
                                        'options' => ['id' => $index . '_10_' . $model->district_id],
                                        'contentOptions' => ['style' => 'text-align: center;  vertical-align:middle;'],
                                        'placement'=>'left',
                                        //'disabled'=>!Yii::$app->user->can('access-finance-disbursement'),
                                        'name'=>'district',
                                        'asPopover' => true,
                                        'value'=>function ($model, $key, $index, $widget) {
                                            if($model->district_id)
                                                return $model->district->name;
                                            else
                                                return '-';
                                        },
                                        'inputType' => Editable::INPUT_DROPDOWN_LIST,
                                        'data'=>ArrayHelper::map(Requestdistrict::find()->all(),'request_district_id','name'),
                                        'formOptions'=>['action' => ['/finance/request/updatedistrict']], // point to the new action
                                    ];
                                },
                                'headerOptions' => ['style' => 'text-align: center'],
                                'contentOptions' => ['style' => 'text-align: center; vertical-align:middle;'],
                                'hAlign'=>'right',
                                //'vAlign'=>'middle',
                                'width'=>'250px',
                            ],
                            [
                                'class' => kartik\grid\ActionColumn::className(),
                                'template' => '{view}',
                                'buttons' => [

                                    'view' => function ($url, $model){
                                        return Html::button('<span class="glyphicon glyphicon-eye-open"></span>', ['value' => '/finance/request/view?id=' . $model->request_id,'onclick'=>'location.href=this.value', 'class' => 'btn btn-primary', 'title' => Yii::t('app', "View Request")]);
                                    },
                                ],
                            ],
                    ],
            'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
            'headerRowOptions' => ['class' => 'kartik-sheet-style'],
            'filterRowOptions' => ['class' => 'kartik-sheet-style'],
            'pjax' => true, // pjax is set to always true for this demo
            'panel' => [
                    'heading' => '',
                    'type' => GridView::TYPE_PRIMARY,
                    'before'=>Html::button('New Request', ['value' => Url::to(['request/create']), 'title' => 'Create Request', 'class' => 'btn btn-info', 'style'=>'margin-right: 6px;', 'id'=>'buttonCreateRequest']),
                    'after'=>false,
                ],
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
        <?php Pjax::end(); ?>
</div>