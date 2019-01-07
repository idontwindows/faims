<?php
/**
 * Created by Larry Mark B. Somocor.
 * User: Larry
 * Date: 3/16/2018
 * Time: 9:16 AM
 */

use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use common\models\procurement\Purchaserequest;
use common\models\procurement\Section;
use common\models\procurement\Division;
use yii\helpers\ArrayHelper;
use yii\widgets\DetailView;
/* @var $this yii\web\View */
/* @var $searchModel common\models\procurement\PurchaserequestSearch */
/* @var $model common\models\procurement\Lineitembudget */
/* @var $form yii\widgets\ActiveForm */


?>

<div class="purchaserequest-modal">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'purchase_request_id',
            'purchase_request_number',
            'purchase_request_date',
            'purchase_request_purpose',
            'purchase_request_referrence_no',
            'purchase_request_project_name',
            'purchase_request_location_project',
        ],
    ]) ?>

</div>
