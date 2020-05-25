<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\pos\models\CategoryProduct */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Category Product',
]) . $model->category_product_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Category Products'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->category_product_id, 'url' => ['view', 'id' => $model->category_product_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="category-product-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
