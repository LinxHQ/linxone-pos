<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\time\TimePicker;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\modules\course\models\Classc */
/* @var $form yii\widgets\ActiveForm */
$is_update = 0;
if(isset($_GET['is_update']))
    $is_update = $_GET['is_update'];
?>

<div class="classc-form">
    <?php $form = ActiveForm::begin(['options'=>['enctype'=>"multipart/form-data"]]); ?>
        <div id="members-index" class="parkclub-wrapper parkclub-wrapper-search ">
            <div class="parkclub-rectangle parkclub-shadow">
               <div class="parkclub-invoice">
                    <div class="parkclub-newm-left-title">
                        <div class="parkclub-header-left">
                            <a href="<?php echo Yii::$app->urlManager->createUrl(['/course/default/index']); ?>"<i class="glyphicon glyphicon-circle-arrow-left"></i></a>
                            <?php echo Yii::t('app', 'Class'); ?>
                        </div>
                    </div>
                   <div class="parkclub-newm ">
                       <fieldset>
                            <?php echo $label_note; ?>
                            <label for=""><?php echo Yii::t('app', 'Name'); ?></label>
                            <?= $form->field($model, 'class_name')->textInput(['maxlength' => true])->label(false); ?>
                            
                            <label for=""><?php echo Yii::t('app', 'Content'); ?></label>
                            <?= $form->field($model, 'class_content')->textarea(['rows' => 6])->label(false); ?>
                            
                            <label for=""><?php echo Yii::t('app', 'Teacher'); ?></label>
                            <div style="width: 90%;left: 5%;position: relative;">
                            <?php 
                                $select_trainer = (isset($model->teacher_id)) ? $model->teacher_id : "";
                                echo Select2::widget([
                                    'model'=>$model,
                                    'name' => 'teacher_id',
                                    'data' => $modelTrainer,
                                    'value'=>$select_trainer,
                                    'options' => [
                                        'placeholder' => Yii::t('app','Select trainers'),
                    //                    'multiple' => true                
                                        ],
                                ]);
                            ?>
                            </div>
                            <br>
                            <?php $model->class_number_session = (isset($model->class_number_session)) ? $model->class_number_session : 0;?>
                            <label for=""><?php echo Yii::t('app', 'Total sessions'); ?></label>
                            <?= $form->field($model, 'class_number_session')->textInput(['maxlength' => true])->label(false); ?>
                            
                            <label for=""><?php echo Yii::t('app', 'Class schedule'); ?></label>
                            <div style="width: 90%;left: 5%;position: relative;">
                            <?php 
                                $select_trainer = (isset($model->class_schedule)) ? $model->getSchedule() : "";
                                echo Select2::widget([
                                    'model'=>$model,
                                    'name' => 'class_schedule',
                                    'data' => app\models\ListSetup::getWeek(),
                                    'value'=>$select_trainer,
                                    'options' => [
                                        'placeholder' => Yii::t('app','Select Schedule'),
                                        'multiple' => true                
                                        ],
                                ]);
                            ?>
                            </div>
                            <br>
                            <div style="width: 91%; margin-left: 5%; margin-bottom: 15px;">
                                <div class="col-md-6" style="padding-left: 0px;">
                                    <label for="" style="margin-left: 5%"><?php echo Yii::t('app', 'Start time'); ?></label>
                                            <?php $model->class_start_time = (isset($model->class_start_time)) ? $model->class_start_time : "06:00";?>
                                            <?php
                                                echo TimePicker::widget([
                                                        'model' => $model,
                                                        'attribute' => 'class_start_time',
                                                        'value' => '06:00',
                                                        'pluginOptions' => [
                                                            'showMeridian' => false,
                                                        ]
                                                ]);
                                            ?>   
                                </div>
                                <div class="col-md-6" style="padding-left: 0px;">
                                    <label for="" style="margin-left: 5%"><?php echo Yii::t('app', 'End time'); ?></label>
                                        <?php $model->class_end_time = (isset($model->class_end_time)) ? $model->class_end_time : "06:00";?>
                                        <?php
                                            echo TimePicker::widget([
                                                    'model' => $model,
                                                    'attribute' => 'class_end_time',
                                                    'value' => '06:00',
                                                    'pluginOptions' => [
                                                        'showMeridian' => false,
                                                    ] 
                                            ]);
                                        ?>   
                                </div>
                            </div>
                            <div style="clear: both">&nbsp;</div>
                            <?php
                                echo$form->field($model, 'class_start_date')->widget(DatePicker::classname(), [
                                    'options' => ['value' =>  $model->class_start_date],
                                    'pluginOptions' => [
                                        'autoclose'=>true,
                                        'format' => 'yyyy-mm-dd',
                                        'startDate'=> date('Y-m-d')
                                    ],
                                    'type' => DatePicker::TYPE_COMPONENT_APPEND,
                                ]);
                            ?>
                            <br>
                            
                       </fieldset>
                   </div>
                   <div class="parkclub-footer" style="text-align: center">
                        <div class="form-group">
                            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                        </div>
                   </div>
               </div>
            </div>
        </div>

    <?php ActiveForm::end(); ?>

</div>

<script type="text/javascript">
$(document).ready(function(){
    var is_update = '<?php echo $is_update ?>';
    if(is_update==1){
        swal({
          title: "",
          text:'<?php echo Yii::t('app',"Bạn có muốn cập nhật lại lịch các buổi học tiếp theo không?");?>',
          showCancelButton: true,
          confirmButtonClass: "btn-danger",
          cancelButtonClass: "btn-primary",
          confirmButtonText: '<?php echo Yii::t('app',"Cancel"); ?>',
          cancelButtonText: '<?php echo Yii::t('app',"Update"); ?>',
          closeOnConfirm: false,
          closeOnCancel: false
        },
        function(isConfirm) {   
          if (isConfirm) {
              location.href="<?php echo Yii::$app->urlManager->createUrl('/course/default/view?id='.$model->course_id); ?>";
          } else {
                updateSchedule();
            }
        });
    }
})

function updateSchedule(){
    $.ajax({
        type:'POST',
        url:'<?php echo Yii::$app->urlManager->createUrl('/course/classc/update-schedule'); ?>',
        data:{id:'<?php echo $model->class_id ?>'},
        success:function(data){
            data = jQuery.parseJSON(data);
            if(data.status=="success"){
                swal({
                        title:'<?php echo Yii::t('app',"Successfuly"); ?>', 
                        closeOnConfirm:false,
                        type:'success'
                    },
                    function(){
                      location.href="<?php echo Yii::$app->urlManager->createUrl('/course/default/view?id='.$model->course_id); ?>";
                    }
                );
            }else{
                alert(data.status);
            }
        }
    })
}
</script>
