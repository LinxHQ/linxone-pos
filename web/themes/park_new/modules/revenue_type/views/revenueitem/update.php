<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\revenue_type\models\RevenueItem */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Revenue Item',
]) . $model->revenue_item_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Revenue Items'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->revenue_item_id, 'url' => ['view', 'id' => $model->revenue_item_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="revenue-item-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
