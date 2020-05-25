<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\history\models\HistorySearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="history-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'history_id') ?>

    <?= $form->field($model, 'history_user') ?>

    <?= $form->field($model, 'history_action') ?>

    <?= $form->field($model, 'history_date') ?>

    <?= $form->field($model, 'history_item') ?>

    <?php // echo $form->field($model, 'history_table') ?>

    <?php // echo $form->field($model, 'history_module') ?>

    <?php // echo $form->field($model, 'history_description') ?>

    <?php // echo $form->field($model, 'history_content') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
