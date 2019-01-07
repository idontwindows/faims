<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\procurement\LineItemBudget */

$this->title = 'Create Line Item Budget';
$this->params['breadcrumbs'][] = ['label' => 'Line Item Budgets', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="line-item-budget-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
