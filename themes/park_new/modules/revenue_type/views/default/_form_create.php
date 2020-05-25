<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;
use app\modules\revenue_type\models\RevenueItem;
use app\models\ListSetup;

/* @var $this yii\web\View */
/* @var $model app\modules\revenue_type\models\Revenue */
/* @var $form yii\widgets\ActiveForm */
$ListSetup = new ListSetup();
?>

<div class="revenue-form">
    <?php $form = ActiveForm::begin(); ?>
        <div id="members-index" class="parkclub-wrapper parkclub-wrapper-search ">
            <div class="parkclub-rectangle parkclub-shadow">
               <div class="parkclub-invoice">
                    <div class="parkclub-newm-left-title">
                        <div class="parkclub-header-left">
                            <a href="<?php echo YII::$app->urlManager->createUrl(['revenue_type/default/index']); ?>"><i class="glyphicon glyphicon-circle-arrow-left"></i></a>
                            <?php echo Yii::t('app', 'Revenue'); ?>
                        </div>
                    </div>
                   <div class="parkclub-newm ">
                        <fieldset>
                            <label for=""><?php echo Yii::t('app', 'Name'); ?></label>
                            <?= $form->field($model, 'revenue_name')->textInput(['maxlength' => true])->label(false); ?>
                            
                            <label for=""><?php echo Yii::t('app', 'Description'); ?></label>
                            <?= $form->field($model, 'revenue_description')->textarea(['rows' => 6])->label(false); ?>
                            
                            <label for=""><?php echo Yii::t('app', 'Module'); ?></label>
                            <?= $form->field($model, 'entry_type')->dropDownList(ListSetup::getItemByList('revenue'))->label(false); ?>
                        </fieldset>
                   </div>
                    
                   <div class="parkclub-footer" style="text-align: center">
                       <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-success']) ?>
                   </div>
               </div>
            </div>
        </div>
    

    <?php ActiveForm::end(); ?>
    

</div>
