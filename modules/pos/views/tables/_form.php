<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\pos\models\Tables */
/* @var $form yii\widgets\ActiveForm */
$category_table = new app\modules\pos\models\CategoryTable();
?>

<div class="tables-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'table_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'category_table_id')->dropDownList($category_table->getDataArray()) ?>
    
    <?= $form->field($model, 'table_description')->textarea(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'table_status')->checkbox([$model->table_status = TRUE,'label'=>Yii::t('app','Active')]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
