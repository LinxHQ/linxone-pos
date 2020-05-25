<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ListsetupSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="list-setup-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'list_id') ?>

    <?= $form->field($model, 'list_name') ?>

    <?= $form->field($model, 'list_parent') ?>

    <?= $form->field($model, 'list_value') ?>

    <?= $form->field($model, 'list_description') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
