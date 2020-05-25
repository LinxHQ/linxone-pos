<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\course\models\ClasscSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="classc-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'class_id') ?>

    <?= $form->field($model, 'course_id') ?>

    <?= $form->field($model, 'teacher_id') ?>

    <?= $form->field($model, 'class_name') ?>

    <?= $form->field($model, 'class_content') ?>

    <?php // echo $form->field($model, 'class_schedule') ?>

    <?php // echo $form->field($model, 'class_number_session') ?>

    <?php // echo $form->field($model, 'class_status') ?>

    <?php // echo $form->field($model, 'class_created_by') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
