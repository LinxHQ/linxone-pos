<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\pos\models\CategoryProductSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="category-product-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'category_product_id') ?>

    <?= $form->field($model, 'category_product_name') ?>

    <?= $form->field($model, 'category_product_description') ?>

    <?= $form->field($model, 'category_product_created_by') ?>

    <?= $form->field($model, 'category_product_parent') ?>

    <?php // echo $form->field($model, 'category_product_status') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
