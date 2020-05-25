<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Facility */
/* @var $form yii\widgets\ActiveForm */

if(!$model->facility_id)
{
    $model->facitily_createdate=date('Y-m-d');
    $model->facitily_status=date('Y-m-d');
}
?>

<div class="facility-form">
    <?php $form = ActiveForm::begin(); ?>
        <div id="members-index" class="parkclub-wrapper parkclub-wrapper-search ">
            <div class="parkclub-rectangle parkclub-shadow">
               <div class="parkclub-invoice">
                    <div class="parkclub-newm-left-title">
                        <div class="parkclub-header-left">
                            <i class="glyphicon glyphicon-circle-arrow-left"></i>
                            <?php echo Yii::t('app', 'Facility'); ?>
                        </div>
                    </div>
                   <div class="parkclub-newm ">
                        <fieldset>
                            <label for=""><?php echo Yii::t('app', 'First Name'); ?></label>
                            <?= $form->field($model, 'facility_name')->textInput(['maxlength' => true])->label(false); ?>
                            
                            <label for=""><?php echo Yii::t('app', 'Description'); ?></label>
                            <?= $form->field($model, 'facility_description')->textarea(['rows' => 6])->label(false); ?>
                            
                            <div class="parkclub-radio">
                            <div class="parkclub-switch">
                                <input value="0" name="Facility[facility_free]" type="radio" <?php echo (($model->facility_free) ? '' : 'checked'); ?> id="radio1">
                                <label for="radio1"><?php echo Yii::t('app', 'CHARGE'); ?></label>
                                <input value="1" name="Facility[facility_free]" type="radio" <?php echo (($model->facility_free) ? 'checked' : ''); ?> id="radio2">
                                <label for="radio2"><?php echo Yii::t('app', 'FREE'); ?></label>
                            </div>
                            </div>
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
    $( document ).ready(function() {
        var tour_step = '<?php echo Yii::$app->session['tour-step']; ?>';
        if(tour_step=='<?php echo app\models\Config::TOUR_FACILITY; ?>')
        {
            tour_facility.restart();
            tour_facility.start();
            tour_facility.goTo(2);
        }
    });
</script>