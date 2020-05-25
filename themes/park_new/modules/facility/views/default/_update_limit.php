<?php 
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<div class="limit-form">

    <?php $form = ActiveForm::begin(); ?>
        <div id="members-index" class="parkclub-wrapper parkclub-wrapper-search ">
            <div class="parkclub-rectangle parkclub-shadow">
               <div class="parkclub-invoice">
                    <div class="parkclub-newm-left-title">
                        <div class="parkclub-header-left">
							<a href="<?php echo YII::$app->urlManager->createUrl(['facility/default/view','id'=>$model->facility_id]); ?>">
								<i class="glyphicon glyphicon-circle-arrow-left"></i>
                            </a>
							<?php echo Yii::t('app', 'Update Limit'); ?>
                        </div>
                    </div>
                    <div class="parkclub-newm ">
                        <fieldset>
                            <label for=""><?php echo Yii::t('app', 'Membership type'); ?></label>
                            <?= $form->field($model, 'membership_type_id')->dropDownList($membershipTypeDropdow)->label(false); ?>
                            <label for=""><?php echo Yii::t('app', 'Limit of Days'); ?></label>
                            <?= $form->field($model, 'faclity_limit_days')->textInput()->label(false); ?>
                            <label for=""><?php echo Yii::t('app', 'Limit of Weeks'); ?></label>
                            <?= $form->field($model, 'faclity_limit_week')->textInput()->label(false); ?>
                            <label for=""><?php echo Yii::t('app', 'Limit of Months'); ?></label>
                            <?= $form->field($model, 'facility_limit_month')->textInput()->label(false); ?>
                        </fieldset>
                    </div>
                   <div class="parkclub-footer" style="text-align: center">
                       <?= Html::submitButton( Yii::t('app', 'Update'), ['class' =>'btn btn-success']) ?>
                   </div>
               </div>
            </div>
        </div>
    <?php ActiveForm::end(); ?>

</div>