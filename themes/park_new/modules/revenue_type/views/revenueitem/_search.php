<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\revenue_type\models\RevenueItemSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="revenue-item-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'revenue_item_id') ?>

    <?= $form->field($model, 'revenue_id') ?>

    <?= $form->field($model, 'revenue_item_price') ?>

    <?= $form->field($model, 'revenue_item_tax') ?>

    <?= $form->field($model, 'revenue_item_price_after_tax') ?>

    <?php // echo $form->field($model, 'revenue_item_name') ?>

    <?php // echo $form->field($model, 'revenue_item_description') ?>

    <?php // echo $form->field($model, 'revenue_item_create_by') ?>

    <?php // echo $form->field($model, 'revenue_item_create_date') ?>

    <?php // echo $form->field($model, 'revenue_item_status') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
