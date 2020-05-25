<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\pos\models\CategoryProduct */

$this->title = $model->category_product_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Category Products'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-product-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->category_product_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->category_product_id], [
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
            'category_product_id',
            'category_product_name',
            'category_product_description',
            'category_product_created_by',
            'category_product_parent',
            'category_product_status',
        ],
    ]) ?>

</div>
