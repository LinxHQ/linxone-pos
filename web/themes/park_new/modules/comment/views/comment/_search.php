<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\comment\models\CommentSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="comment-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'comment_id') ?>

    <?= $form->field($model, 'comment_content') ?>

    <?= $form->field($model, 'comment_create_date') ?>

    <?= $form->field($model, 'comment_create_by') ?>

    <?= $form->field($model, 'comment_status') ?>

    <?php // echo $form->field($model, 'comment_parent') ?>

    <?php // echo $form->field($model, 'comment_entity_id') ?>

    <?php // echo $form->field($model, 'comment_entity_type') ?>

    <?php // echo $form->field($model, 'rating') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
