<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\pos\models\CategoryProduct */

$this->title = Yii::t('app', 'Create Category Product');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Category Products'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-product-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
