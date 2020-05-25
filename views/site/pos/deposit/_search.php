<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\pos\models\DepositSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="deposit-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'deposit_id') ?>

    <?= $form->field($model, 'member_id') ?>

    <?= $form->field($model, 'deposit_no') ?>

    <?= $form->field($model, 'deposit_name') ?>

    <?= $form->field($model, 'deposit_phone') ?>

    <?php // echo $form->field($model, 'deposit_email') ?>

    <?php // echo $form->field($model, 'deposit_address') ?>

    <?php // echo $form->field($model, 'deposit_note') ?>

    <?php // echo $form->field($model, 'deposit_status') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
