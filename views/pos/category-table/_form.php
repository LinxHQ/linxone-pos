<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\pos\models\CategoryTable */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="category-table-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'category_table_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'category_table_description')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'category_table_create_by')->textInput() ?>

    <?= $form->field($model, 'category_table_parent')->textInput() ?>

    <?= $form->field($model, 'category_table_status')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
