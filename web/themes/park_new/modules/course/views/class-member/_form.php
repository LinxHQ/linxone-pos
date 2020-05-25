<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\course\models\ClassMember */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="class-member-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'class_id')->textInput() ?>

    <?= $form->field($model, 'class_member_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'class_member_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'class_member_email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'class_member_phone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'class_member_date')->textInput() ?>

    <?= $form->field($model, 'class_member_status')->textInput() ?>

    <?= $form->field($model, 'class_member_note')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'member_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
