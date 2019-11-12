<?php
use kartik\grid\GridView;
use kartik\select2\Select2;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

use yii\widgets\ActiveForm;

use common\models\procurementplan\Itemcategory;
use common\models\procurementplan\Ppmpitem;
use common\models\procurementplan\Unitofmeasure;
/* @var $this yii\web\View */
/* @var $model common\models\procurementplan\Ppmpitem */
/* @var $form yii\widgets\ActiveForm */
?>

<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'id'=>'ppmp_items', //additional
        'pjax' => true, // pjax is set to always true for this demo
                'pjaxSettings' => [
                        'options' => [
                            'enablePushState' => false,
                        ]
                    ],
        'columns' => [
            [
                'class' => '\kartik\grid\CheckboxColumn',
                'headerOptions' => ['class' => 'kartik-sheet-style'],
                'name'=>'ppmp-item', //additional
                'checkboxOptions' => function($model, $key, $index, $column) use ($id){
                                         $bool = Ppmpitem::find()->where(['ppmp_id' => $id, 'item_id' => $model->item_id, 'active'=>1])->count();
                                         return ['checked' => $bool,
                                                'onclick'=>'onPPMP(this.value,this.checked)' //additional
                                                ];
                                     }
            ],
            [
                'attribute' => 'item_category_id',
                'value'=>function ($model, $key, $index, $widget) { 
                            return $model->itemcategory->category_name;
                        },
                'filter' => ArrayHelper::map(Itemcategory::find()->asArray()->all(), 'item_category_id', 'category_name'),
                'filterType' => GridView::FILTER_SELECT2,
                /*'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => 'Select'],*/
                
                'group'=>true,  // enable grouping,
                'groupedRow'=>true,                    // move grouped column to a single grouped row
                'groupOddCssClass'=>'kv-grouped-row',  // configure odd group cell css class
                'groupEvenCssClass'=>'kv-grouped-row', // configure even group cell css class
            ],
            [
                'attribute' => 'item_name',
                'value'=>function ($model, $key, $index, $widget){ 
                            return $model->item_name.' ('.$model->item_code.')';
                        },
            ],
            [
                'attribute' => 'unit_of_measure_id',
                'value'=>function ($model, $key, $index, $widget){ 
                            return $model->getUnit();
                        },
            ],
            //'price_catalogue',
            [
                'attribute' => 'price_catalogue',
                'hAlign'=>'right',
                'value'=>function ($model, $key, $index, $widget){ 
                            $fmt = Yii::$app->formatter;
                            //return $model->getUnit();
                            return $fmt->asDecimal($model->price_catalogue);
                        },
            ],
        ],
    ]); 
?>
<script type="text/javascript">
function onPPMP(item_id,checked){
    var ppmp_id = <?php echo $id?>;
    var ppmp_year = <?php echo $year?>;
    $.ajax({
            type: "POST",
            url: "<?php echo Url::to(['ppmp/additems']); ?>",
            data: {itemId:item_id,ppmpId:ppmp_id,year:ppmp_year,checked:checked},
            success: function(data){ 
                }
            });

    return false;
  //});
}    
</script>


