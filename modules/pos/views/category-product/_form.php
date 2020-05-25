<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\pos\models\CategoryProduct */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="category-product-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'category_product_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'category_product_description')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'category_product_created_by')->textInput() ?>

    <?= $form->field($model, 'category_product_parent')->textInput() ?>

    <?= $form->field($model, 'category_product_status')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
