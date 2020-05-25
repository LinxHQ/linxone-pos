<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\date\DatePicker;
use kartik\time\TimePicker;

?>

    
    <?php $form = ActiveForm::begin(); ?>
        <div class="parkclub-newm ">
        <fieldset>
             <label for=""><?php echo Yii::t('app', 'Start Date'); ?></label>
             <?php
                 $model->class_session_date = ((isset($model->class_session_date) && $model->class_session_date !=NULL && $model->class_session_date!="")
                            ? $model->class_session_date : date('Y-m-d'));
                 echo $form->field($model, 'class_session_date')->widget(DatePicker::classname(), [
                     'pluginOptions' => [
                         'autoclose'=>true,
                         'format' => 'yyyy-m-dd',
                         'startDate'=> date('Y-m-d')
                     ],
                     'type' => DatePicker::TYPE_COMPONENT_APPEND,
                     'pluginEvents' => [
                         'change' => 'function() { loadStarttime(); }',
                         ],
                 ])->label(false);  
             ?>
             <br>
             <div style="width: 91%; margin-left: 5%; margin-bottom: 15px;">
                 <div class="col-md-6" style="padding-left: 0px;">
                     <label for="" style="margin-left: 5%"><?php echo Yii::t('app', 'Start time'); ?></label>
                             <?php $model->class_session_start_time = (isset($model->class_session_start_time)) ? $model->class_session_start_time : "06:00";?>
                             <?php
                                 echo TimePicker::widget([
                                         'model' => $model,
                                         'attribute' => 'class_session_start_time',
                                         'value' => '06:00',
                                         'pluginOptions' => [
                                             'showMeridian' => false,
                                         ]
                                 ]);
                             ?>   
                 </div>
                 <div class="col-md-6" style="padding-left: 0px;">
                     <label for="" style="margin-left: 5%"><?php echo Yii::t('app', 'End time'); ?></label>
                         <?php $model->class_session_end_time = (isset($model->class_session_end_time)) ? $model->class_session_end_time : "06:00";?>
                         <?php
                             echo TimePicker::widget([
                                     'model' => $model,
                                     'attribute' => 'class_session_end_time',
                                     'value' => '06:00',
                                     'pluginOptions' => [
                                         'showMeridian' => false,
                                     ] 
                             ]);
                         ?>   
                 </div>
             </div>
             <div style="clear: both">&nbsp;</div>


             <label for=""><?php echo Yii::t('app', 'Note'); ?></label>
             <?= $form->field($model, 'class_session_note')->textarea(['rows' => 6])->label(false); ?>

        </fieldset>
        </div>
        <div class="parkclub-footer" style="text-align: center">
             <div class="form-group">
                 <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
             </div>
        </div>
    <?php ActiveForm::end(); ?> 

