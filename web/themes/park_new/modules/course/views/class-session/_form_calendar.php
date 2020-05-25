<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;


if(isset($_GET['my_start_time']) && isset($_GET['faciliti_id'])){
    $model->class_id = $_GET['faciliti_id'];
    $model->class_session_date = $_GET['start'];
    $model->class_session_start_time = $_GET['my_start_time'];
    $model->class_session_end_time = $_GET['my_end_time'];
}

$class = app\modules\course\models\Classc::findOne($model->class_id);
?>

<div class="class-session-form">
    
    <?php $form = ActiveForm::begin(
            ['layout' => 'horizontal',
                'fieldConfig' => [
                    'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
                    'horizontalCssClasses' => [
                        'label' => 'col-sm-4',
                        'offset' => 'col-sm-offset-4',
                        'wrapper' => 'col-sm-8',
                        'error' => '',
                        'hint' => '',
                    ],
                ],
             ]); ?>
    <div style="margin-left: 10%">
        <p><?php echo Yii::t('app', 'Class name').': <span style="margin-left:25px;"><b>'.$class->class_name.'</b></span>'; ?></p>
    </div>
    <div hidden="">
        
        <?= $form->field($model, 'class_id')->textInput() ?>
    </div>  
        
        <?= $form->field($model, 'class_session_date')->textInput(['readonly'=>'true']) ?>

        <?= $form->field($model, 'class_session_start_time')->textInput(['readonly'=>'true']) ?>

        <?= $form->field($model, 'class_session_end_time')->textInput(['readonly'=>'true']) ?>
        <?= $form->field($model, 'class_session_note')->textarea(['maxlength' => true,'rows'=>5]) ?>

    <div class="parkclub-footer" style="text-align: center">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-success','id'=>'submit']) ?>
    </div>
    
    <?php ActiveForm::end(); ?>

</div>