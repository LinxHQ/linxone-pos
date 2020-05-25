<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\pos\models\Deposit */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="deposit-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'member_id')->textInput() ?>

    <?= $form->field($model, 'deposit_no')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'deposit_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'deposit_phone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'deposit_email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'deposit_address')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'deposit_note')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'deposit_status')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
