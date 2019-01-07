<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\procurement\Obligationrequest */

$this->title = 'Create Obligationrequest';
$this->params['breadcrumbs'][] = ['label' => 'Obligationrequests', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="obligationrequest-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
