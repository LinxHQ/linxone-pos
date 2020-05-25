<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\pos\models\CategoryTableSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="category-table-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'category_table_id') ?>

    <?= $form->field($model, 'category_table_name') ?>

    <?= $form->field($model, 'category_table_description') ?>

    <?= $form->field($model, 'category_table_create_by') ?>

    <?= $form->field($model, 'category_table_parent') ?>

    <?php // echo $form->field($model, 'category_table_status') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
