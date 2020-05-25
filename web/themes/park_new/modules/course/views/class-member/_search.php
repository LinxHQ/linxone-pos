<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\course\models\ClassMemberSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="class-member-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'class_member_id') ?>

    <?= $form->field($model, 'class_id') ?>

    <?= $form->field($model, 'class_member_code') ?>

    <?= $form->field($model, 'class_member_name') ?>

    <?= $form->field($model, 'class_member_email') ?>

    <?php // echo $form->field($model, 'class_member_phone') ?>

    <?php // echo $form->field($model, 'class_member_date') ?>

    <?php // echo $form->field($model, 'class_member_status') ?>

    <?php // echo $form->field($model, 'class_member_note') ?>

    <?php // echo $form->field($model, 'member_id') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
