<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\course\models\ClassSessionSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="class-session-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'class_session_id') ?>

    <?= $form->field($model, 'class_id') ?>

    <?= $form->field($model, 'class_session_start_time') ?>

    <?= $form->field($model, 'class_session_end_time') ?>

    <?= $form->field($model, 'class_session_note') ?>

    <?php // echo $form->field($model, 'class_session_status') ?>

    <?php // echo $form->field($model, 'class_session_date') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
