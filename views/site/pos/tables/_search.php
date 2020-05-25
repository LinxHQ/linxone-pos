<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\pos\models\TablesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tables-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'table_id') ?>

    <?= $form->field($model, 'category_table_id') ?>

    <?= $form->field($model, 'table_name') ?>

    <?= $form->field($model, 'table_order') ?>

    <?= $form->field($model, 'table_status') ?>

    <?php // echo $form->field($model, 'table_created_by') ?>

    <?php // echo $form->field($model, 'table_created_date') ?>

    <?php // echo $form->field($model, 'table_description') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
