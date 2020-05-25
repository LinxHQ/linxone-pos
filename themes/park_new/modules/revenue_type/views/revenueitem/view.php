<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\revenue_type\models\RevenueItem */

$this->title = $model->revenue_item_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Revenue Items'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="revenue-item-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->revenue_item_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->revenue_item_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'revenue_item_id',
            'revenue_id',
            'revenue_item_price',
            'revenue_item_tax',
            'revenue_item_price_after_tax',
            'revenue_item_name',
            'revenue_item_description',
            'revenue_item_create_by',
            'revenue_item_create_date',
            'revenue_item_status',
        ],
    ]) ?>

</div>
