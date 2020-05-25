<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\checkin_entity\models\MemberRegisterSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="member-register-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'register_entity_id') ?>

    <?= $form->field($model, 'entity_id') ?>

    <?= $form->field($model, 'entity_type') ?>

    <?= $form->field($model, 'member_code') ?>

    <?= $form->field($model, 'member_name') ?>

    <?php // echo $form->field($model, 'member_email') ?>

    <?php // echo $form->field($model, 'member_phone') ?>

    <?php // echo $form->field($model, 'member_date') ?>

    <?php // echo $form->field($model, 'member_status') ?>

    <?php // echo $form->field($model, 'member_note') ?>

    <?php // echo $form->field($model, 'member_id') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
