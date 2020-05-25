<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\members\models\Members;
use kartik\datetime\DateTimePicker;
use app\models\User;
use kartik\select2\Select2;
use kartik\date\DatePicker;
use app\modules\invoice\models\InvoiceItem;
use app\modules\revenue_type\models\RevenueItem;

/* @var $this yii\web\View */
/* @var $model app\modules\training\models\MemberTrainings */
/* @var $form yii\widgets\ActiveForm */

$Members=new Members();
$model->member_id=$_GET['member_id'];
$ListSetup = new \app\models\ListSetup();
$revenue = new RevenueItem();
//echo '<pre>';
//print_r($modelUser);

?>

<div class="member-trainings-form">
    <?php $form = ActiveForm::begin(['options' => ['onSubmit'=>"return berfore_submit_form(); "]]); ?>
    <div id="members-index" class="parkclub-wrapper parkclub-wrapper-search ">
        <div class="parkclub-rectangle parkclub-shadow">
           <div class="parkclub-invoice">
                <div class="parkclub-newm-left-title">
                    <div class="parkclub-header-left">
                        <a href="<?php echo YII::$app->urlManager->createUrl(['members/default/update?id='.$_GET['member_id']]); ?>"><i class="glyphicon glyphicon-circle-arrow-left"></i></a>
                        <?php echo $label; ?>
                    </div>
                </div>
               <div class="parkclub-newm ">
                    <fieldset>
                    <label for=""><?php echo Yii::t('app','Member'); ?>:</label>
                    <?= $form->field($model, 'member_id')->dropDownList($Members->getDataDropdown(false,false,true),['onchange'=>'loadMemberShip(this.value);','disabled'=>'true'])->label(false); ?>
                    
                    <label for=""><?php echo Yii::t('app','Package'); ?>:</label>
                    <?= $form->field($model, 'package_id')->dropDownList($revenue->getRevenueItemByEntry(2,'array','index'))->label(false); ?>
                    
                    <label for=""><?php echo Yii::t('app', 'Total sessions'); ?></label>
                    <?= $form->field($model, 'training_total_sessions')->textInput()->label(false); ?>
                    
                    <label for=""><?php echo Yii::t('app', 'Trainers'); ?></label>
                    <div style="width: 90%;left: 5%;position: relative;">
                    <?php 
                        $select_trainer ="";
                        if($ModelMemberTrainer)
                            $select_trainer = $ModelMemberTrainer->trainer_user_id;
                        echo Select2::widget([
                            'model'=>$modelTrainer,
                            'name' => 'trainer_user_id',
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
                    <label for=""><?php echo Yii::t('app', 'Status'); ?></label>
                    <?= $form->field($model, 'member_training_status')->dropDownList($Members->getArrayStatusTraining())->label(false) ?>

                    <span id="error_trainer" style="display: none; color: #a94442;"><?php echo Yii::t('app','Trainer Name cannot be blank.'); ?></span>
                </fieldset>
               </div><br>
           <div class="parkclub-footer" style="text-align: center">
               <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-success']) ?>
           </div>
        </div>
    </div>
    </div>
    
    <?php ActiveForm::end(); ?>

</div>
<script>
function berfore_submit_form()
{
    var member_trainer = $("select[name='trainer_user_id']").val();
    if(member_trainer == null || member_trainer=="")
    {
        $('#error_trainer').show();
        return false;
    }
    else {
        $('#error_trainer').hide();
		$.blockUI();
	}	
    return true;
}

</script>