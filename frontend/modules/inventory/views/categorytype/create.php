<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\inventory\Categorytype */

$this->title = 'Create Categorytype';
$this->params['breadcrumbs'][] = ['label' => 'Categorytypes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="categorytype-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
