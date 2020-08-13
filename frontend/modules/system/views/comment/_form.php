<?php
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model common\models\system\Comment */
/* @var $form yii\widgets\ActiveForm */
?>

<?php
/*$this->registerJs(
   '$("document").ready(function(){ 
		$("#new_`").on("pjax:end", function() {
			$.pjax.reload({container:"#comments"});  //Reload GridView
		});
    });'
);*/

?>



<div class="comment-form">

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            //'filterModel' => $searchModel,
            'id'=>'expenditure_objects', //additional
            'pjax' => true, // pjax is set to always true for this demo
            'pjaxSettings' => [
                    'options' => [
                        'enablePushState' => false,
                    ]
                ],
            'columns' => [
                [
                        'class' => 'kartik\grid\SerialColumn',
                        //'contentOptions' => ['class' => 'kartik-sheet-style'],
                        //'width' => '20px',
                        'header' => '',
                        'headerOptions' => ['class' => 'kartik-sheet-style'],
                        //'mergeHeader' => true,
                    ],
                [
                    'attribute' => 'message',
                    'value'=>function ($model, $key, $index, $widget){ 
                                return $model->message;
                            },
                ],
        ]]); ?>
        
        <!--?= $this->render('_comments', ['dataProvider' => $dataProvider]) ?-->



    
    <?php $form = ActiveForm::begin(['id'=>'comment-form']); ?>

    <?= $form->field($model, 'component_id')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'record_id')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'created_by')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'message')->textarea(['rows' => 3]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Post' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'id'=>'post-comment', 'onclick'=>'onPostComment('.$model->component_id.','.$model->record_id.','.$model->created_by.','.$model->message.')']) ?>
    </div>

    <?php ActiveForm::end(); ?>
        

</div>

<script type="text/javascript">

function onPostComment(component_id,record_id,created_by,msg){
    $.ajax({
            type: "POST",
            url: "<?php echo Url::to(['/system/comment/post']); ?>",
            data: {componentid:component_id,recordid:record_id,createdby:created_by,msg:msg},
            success: function(data){ 
                return false;
                }
            });

    //return false;
  //});
}    
</script>