<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\event\models\EventSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="event-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'event_id') ?>

    <?= $form->field($model, 'event_name') ?>

    <?= $form->field($model, 'event_content') ?>

    <?= $form->field($model, 'event_change') ?>

    <?= $form->field($model, 'event_amount') ?>

    <?php // echo $form->field($model, 'event_create_by') ?>

    <?php // echo $form->field($model, 'event_created_date') ?>

    <?php // echo $form->field($model, 'event_order') ?>

    <?php // echo $form->field($model, 'event_status') ?>

    <?php // echo $form->field($model, 'event_start_time') ?>

    <?php // echo $form->field($model, 'event_end_time') ?>

    <?php // echo $form->field($model, 'event_person_in_charge') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
