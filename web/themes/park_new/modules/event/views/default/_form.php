<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datetime\DateTimePicker;
use kartik\time\TimePicker;
use kartik\date\DatePicker;
use app\models\ListSetup;
use kartik\select2\Select2;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\modules\event\models\Event */
/* @var $form yii\widgets\ActiveForm */
$ListSetup = new ListSetup();
$user = new User();
?>
<div class="facility-form">
<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data','onSubmit'=>"return check_data(); ",'name'=>'w0[]']]);  ?>

<div id="members-index" class="parkclub-wrapper parkclub-wrapper-search ">
    <div class="parkclub-rectangle parkclub-shadow">
        <div class="parkclub-invoice">
           
                <div class="parkclub-header-left">
                    <a href="<?php echo YII::$app->urlManager->createUrl(['event/default/index']); ?>"><i class="glyphicon glyphicon-circle-arrow-left"></i></a>
                    <?php echo Yii::t('app', 'Event'); ?>
                </div>
           
        <table class="table parkclub-table" style="text-align: left;margin-bottom: 0;">
            <tr>
                <td><?php echo Yii::t('app','Event name'); ?>:</td>
                <td><?= $form->field($model, 'event_name')->textInput(['maxlength' => true])->label(false); ?></td>
                <td><?php echo Yii::t('app','Event content'); ?>:</td>
                <td><?= $form->field($model, 'event_content')->textInput(['maxlength' => true])->label(false); ?></td>
            </tr>
            <div id="error_event_name" style="color:#a94442" class="parkclub-newm-error"  hidden=""><?php echo Yii::t('app', 'Event name can not be blank.'); ?></div>
            
            <tr>
                <td><?php echo Yii::t('app','Start date'); ?>:</td>
                <td><?php
                                echo$form->field($model, 'event_start_date')->widget(DatePicker::classname(), [
                                    //'options' => ['value' =>  $model->event_start_time],
                                    'pluginOptions' => [
                                        'autoclose'=>true,
                                        'format' => 'yyyy-mm-dd',
                                        'startDate'=> date('Y-m-d')
                                    ],
                                    'type' => DatePicker::TYPE_COMPONENT_APPEND,
                                ])->label(false);
                            ?></td>
                <td><?php echo Yii::t('app','End date'); ?>:</td>
                <td><?php
                                echo$form->field($model, 'event_end_date')->widget(DatePicker::classname(), [
                                   // 'options' => ['value' =>  $model->event_end_time],
                                    'pluginOptions' => [
                                        'autoclose'=>true,
                                        'format' => 'yyyy-mm-dd',
                                        'startDate'=> date('Y-m-d')
                                    ],
                                    'type' => DatePicker::TYPE_COMPONENT_APPEND,
                                ])->label(false);
                            ?></td>
            </tr>
            <tr>
                <td><?php echo Yii::t('app','Start Time'); ?>:</td>
                <td><?php $model->event_start_time = (isset($model->event_start_time)) ? $model->event_start_time : "06:00";?>
                                            <?php
                                                echo TimePicker::widget([
                                                        'model' => $model,
                                                        'attribute' => 'event_start_time',
                                                        'value' => '06:00',
                                                        'pluginOptions' => [
                                                            'showMeridian' => false,
                                                        ]
                                                ]);
                                            ?></td>
                <td><?php echo Yii::t('app','End Time'); ?>:</td>
                <td><?php $model->event_end_time = (isset($model->event_end_time)) ? $model->event_end_time : "06:00";?>
                                            <?php
                                                echo TimePicker::widget([
                                                        'model' => $model,
                                                        'attribute' => 'event_end_time',
                                                        'value' => '06:00',
                                                        'pluginOptions' => [
                                                            'showMeridian' => false,
                                                        ]
                                                ]);
                                            ?></td>
            </tr>
            <div id="error_event_start_time" style="color:#a94442" class="parkclub-newm-error"  hidden=""><?php echo Yii::t('app', 'Event start time can not be blank.'); ?></div>
            

            <div id="error_event_end_time" style="color:#a94442" class="parkclub-newm-error"  hidden=""><?php echo Yii::t('app', 'Event end time can not be blank.'); ?></div>

            <tr>
                <td><?php echo Yii::t('app','Event status'); ?>:</td>
                <td><?= $form->field($model, 'event_status')->dropDownList(\app\models\ListSetup::getItemByList('status'))->label(false); ?></td>
                <td><?php echo Yii::t('app','Event person in charge'); ?>:</td>
                <td><?= $form->field($model, 'event_person_in_charge')->dropDownList($user->getUser())->label(false); ?></td>
            </tr>
            
            
            <tr id="course_price" <?php if($model->event_change ==1){echo "hidden";} ?>>
                <td><?php echo Yii::t('app','Event fees'); ?>:</td>
                <td><?= $form->field($model, 'event_amount')->textInput(['maxlength' => true,'value' => ($model->event_amount) ? $model->event_amount : '0'])->label(false); ?></td>
                <td></td>
                <td></td>
            </tr>
       
        </table>
            <div class="parkclub-newm " style="margin-top: -60px;">
                <fieldset>
                    <div class="parkclub-radio">
                        <div class="parkclub-switch">
                            <input value="0" onchange="showPrice(0)" name="Event[event_change]" type="radio" <?php echo (($model->event_change) ? '' : 'checked'); ?> id="radio1">
                            <label for="radio1"><?php echo Yii::t('app', 'CHARGE'); ?></label>
                            <input value="1" onchange="showPrice(1)" name="Event[event_change]" type="radio" <?php echo (($model->event_change) ? 'checked' : ''); ?> id="radio2">
                            <label for="radio2"><?php echo Yii::t('app', 'FREE'); ?></label>
                        </div>
                        <div style="margin-left: 53%;width: 40%;">
                            <?= \yii\bootstrap\Html::input('file', 'file_product[]',"",['multiple'=>'multiple']) ?>
                        </div>
                    </div>
                    
                </fieldset>
            </div>
        
        <div class="parkclub-footer" style="text-align: center">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>
</div>

<script>
function deleteEvent(id){
        if (confirm("Are you sure you want to delete this event?") == true){
            $.ajax({
                type:'POST',
                url:'<?php echo Yii::$app->urlManager->createUrl('event/default/delete'); ?>',
                data:{id:id},
                success:function(data){

                }
            });
        }
    }
function  showPrice(value){
    if(value==0){
        $('#course_price').show();
    }else{
        $('#course_price').hide();
        $('#course_price #course-course_amount').val(0);
    }
}
</script>










