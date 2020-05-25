<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\course\models\Course */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="course-form">

    <?php $form = ActiveForm::begin(['options'=>['enctype'=>"multipart/form-data"]]); ?>
        <div id="members-index" class="parkclub-wrapper parkclub-wrapper-search ">
            <div class="parkclub-rectangle parkclub-shadow">
               <div class="parkclub-invoice">
                    <div class="parkclub-newm-left-title">
                        <div class="parkclub-header-left">
                            <a href="<?php echo Yii::$app->urlManager->createUrl(['/course/default/index']); ?>"<i class="glyphicon glyphicon-circle-arrow-left"></i></a>
                            <?php echo Yii::t('app', 'Course'); ?>
                        </div>
                    </div>
                   <div class="parkclub-newm ">
                        <fieldset>
                            <label for=""><?php echo Yii::t('app', 'Name'); ?></label>
                            <?= $form->field($model, 'course_name')->textInput(['maxlength' => true])->label(false); ?>
                            
                            <label for=""><?php echo Yii::t('app', 'Content'); ?></label>
                            <?= $form->field($model, 'course_content')->textarea(['rows' => 6])->label(false); ?>
                            
                            <div class="parkclub-radio">
                                <div class="parkclub-switch">
                                    <input value="0" onchange="showPrice(0)" name="Course[course_change]" type="radio" <?php echo (($model->course_change) ? '' : 'checked'); ?> id="radio1">
                                    <label for="radio1"><?php echo Yii::t('app', 'CHARGE'); ?></label>
                                    <input value="1" onchange="showPrice(1)" name="Course[course_change]" type="radio" <?php echo (($model->course_change) ? 'checked' : ''); ?> id="radio2">
                                    <label for="radio2"><?php echo Yii::t('app', 'FREE'); ?></label>
                                </div>
                            </div>
                            <div id="course_price">
                            <label for=""><?php echo Yii::t('app', 'Price'); ?></label>
                            <?php 
                                $model->course_amount = ($model->course_amount) ? $model->course_amount : '0';
                            echo $form->field($model, 'course_amount')->textInput(['maxlength' => true])->label(false); ?>
                            </div>
                            
                            <label for=""><?php echo Yii::t('app', 'Images'); ?></label>
                            <?= \yii\bootstrap\Html::input('file', 'file[]',"",['multiple'=>'multiple']) ?>
                            
                            <?php if(isset($model->course_id)){ ?>
                            <div id="view-images" style="margin-left: 5%;"></div><br>
                            <?php } ?>
                            
                        </fieldset>
                   </div>
                    
                   <div class="parkclub-footer" style="text-align: center">
                       <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-success','id'=>'submit-facility']) ?>
                   </div>
               </div>
            </div>
        </div>

    <?php ActiveForm::end(); ?>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        LoadImages();
    })
    function  showPrice(value){
        if(value==0){
            $('#course_price').show();
        }else{
            $('#course_price').hide();
            $('#course_price #course-course_amount').val(0);
        }
    }
    function LoadImages(){
        $('#view-images').load('<?php echo Yii::$app->urlManager->createUrl('/site/update-images'); ?>',
            {entity_id:'<?php echo $model->course_id ?>',entity_type:'course'});
    }
    
    function removeImages(id){
        $.ajax({
            'type':'POST',
            'url':'<?php echo Yii::$app->urlManager->createUrl('site/delete-images'); ?>',
            'data':{id:id},
            'success':function(data){
                data = jQuery.parseJSON(data);
                if(data.status=='success'){
                    $.notify("<?php echo Yii::t('app', 'successfully saved') ?>", "success");
                    $('#view-images').load('<?php echo Yii::$app->urlManager->createUrl('/site/update-images'); ?>',
                        {entity_id:'<?php echo $model->course_id ?>',entity_type:'course'}); 
                }else{
                    alert(data.status);
                }
            }
        });

    }
</script>
