<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\pos\models\ProductSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="product-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'product_id') ?>

    <?= $form->field($model, 'category_product_id') ?>

    <?= $form->field($model, 'product_no') ?>

    <?= $form->field($model, 'product_name') ?>

    <?= $form->field($model, 'product_original') ?>

    <?php // echo $form->field($model, 'product_selling') ?>

    <?php // echo $form->field($model, 'product_qty_out_of_stock') ?>

    <?php // echo $form->field($model, 'product_qty_notify') ?>

    <?php // echo $form->field($model, 'product_description') ?>

    <?php // echo $form->field($model, 'product_status') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
