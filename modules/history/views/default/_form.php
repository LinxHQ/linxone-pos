<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\history\models\History */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="history-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'history_user')->textInput() ?>

    <?= $form->field($model, 'history_action')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'history_date')->textInput() ?>

    <?= $form->field($model, 'history_item')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'history_table')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'history_module')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'history_description')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'history_content')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
