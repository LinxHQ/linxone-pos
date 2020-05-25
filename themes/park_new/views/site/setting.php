<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$this->title = Yii::t('app', 'Setting System');
?>
<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data','name'=>'w0[]']]);  ?>
<div id="members-index" class="parkclub-wrapper parkclub-wrapper-search ">
    <div class="parkclub-rectangle parkclub-shadow">
       <div class="parkclub-invoice">
            <div class="parkclub-newm-left-title">
                <div class="parkclub-header-left">
                    <a href="<?php echo Yii::$app->urlManager->createUrl('/configuration'); ?>"><i class="glyphicon glyphicon-circle-arrow-left"></i></a>
                    <?php echo $this->title; ?>
                </div>
            </div>
           
           <div class="parkclub-newm " style="padding: 40px;overflow: hidden;">
               <div class="col-md-12">
                    <div class="col-md-4">
                       <label for=""><i class="glyphicon glyphicon-play-circle"></i> <?php echo Yii::t('app', 'Canceled booking overdue'); ?></label>
                    </div>
                    <div class="col-md-4">
                       <?php echo \yii\bootstrap\Html::checkbox('cancel_booking_overdue',$model->cancel_booking_overdue,['style'=>'width:10%;height:10px;top: 2px;position: relative;']); ?>
                    </div>
                    
               </div><br><br>
               <div class="col-md-12">
                    <div class="col-md-4">
                       <label for=""><i class="glyphicon glyphicon-play-circle"></i> <?php echo Yii::t('app', 'Tax'); ?></label>
                    </div>
                   <div class="col-md-4">
                       <?php echo \yii\bootstrap\Html::dropDownList('default_tax',$model->default_tax, app\models\ListSetup::getItemByList("Tax"),['class'=>'form-control select-none','style'=>'width:100%;margin-left:0']); ?>
                   </div>
                    
               </div>
               <div class="col-md-12">
                    <div class="col-md-4">
                       <label for=""><i class="glyphicon glyphicon-play-circle"></i> <?php echo Yii::t('app', 'Currency'); ?></label>
                    </div>
                   <div class="col-md-4">
                       <?php echo \yii\bootstrap\Html::dropDownList('currency',$model->currency, app\models\ListSetup::getItemByList("currency"),['class'=>'form-control select-none','style'=>'width:100%;margin-left:0']); ?>
                   </div>
               </div>
               <div class="col-md-12">
                    <div class="col-md-4">
                       <label for=""><i class="glyphicon glyphicon-play-circle"></i> <?php echo Yii::t('app', 'Decimal'); ?></label>
                    </div>
                   <div class="col-md-4">
                       <?php echo \yii\bootstrap\Html::input('text','decimal',$model->decimal) ?>
                   </div>
               </div>
               <div class="col-md-12">
                    <div class="col-md-4">
                       <label for=""><i class="glyphicon glyphicon-play-circle"></i> <?php echo Yii::t('app', 'Decimal Separator'); ?></label>
                    </div>
                   <div class="col-md-4">
                       <?php echo \yii\bootstrap\Html::input('text','decimalSeparator',$model->decimalSeparator) ?>
                   </div>
               </div>
               <div class="col-md-12">
                    <div class="col-md-4">
                       <label for=""><i class="glyphicon glyphicon-play-circle"></i> <?php echo Yii::t('app', 'Thousand Separator'); ?></label>
                    </div>
                   <div class="col-md-4">
                       <?php echo \yii\bootstrap\Html::input('text','thousandSeparator',$model->thousandSeparator) ?>
                   </div>
               </div>
               
               <div class="col-md-12">
                    <div class="col-md-4">
                       <label for=""><i class="glyphicon glyphicon-play-circle"></i> <?php echo Yii::t('app', 'Format date'); ?></label>
                    </div>
                   <div class="col-md-4">
                       <?php echo \yii\bootstrap\Html::radioList('format_date', $model->format_date, app\models\ListSetup::getItemByList('format_date'),['class'=>'list-radio']) ?>
                   </div>
               </div>
               
               <div class="col-md-12">
                    <div class="col-md-4">
                       <label for=""><i class="glyphicon glyphicon-play-circle"></i> <?php echo Yii::t('app', 'Format time'); ?></label>
                    </div>
                   <div class="col-md-4">
                       <?php echo \yii\bootstrap\Html::radioList('format_time', $model->format_time, app\models\ListSetup::getItemByList('format_time'),['class'=>'list-radio']) ?>
                   </div>
               </div>
               <div class="col-md-12" style="margin-top: 10px;">
                    <div class="col-md-4">
                       <label for=""><i class="glyphicon glyphicon-play-circle"></i> <?php echo Yii::t('app', 'Notify days to expiry'); ?></label>
                    </div>
                   <div class="col-md-4">
                       <input type="text" name="date_expiry_membership" value="<?php echo $model->date_expiry_membership  ?>" />
                   </div>
               </div>
			   <div class="col-md-12" style="margin-top: 10px;">
                    <div class="col-md-4">
                       <label for=""><i class="glyphicon glyphicon-play-circle"></i> <?php echo Yii::t('app', 'Customize membership date'); ?></label>
                    </div>
                   <div class="col-md-4">
                       <?php echo \yii\bootstrap\Html::checkbox('customisable_membership_date',$model->customisable_membership_date,['style'=>'width:10%;height:10px;top: 2px;position: relative;']); ?>
                    </div>
               </div>
                
           </div>
           

           <div class="parkclub-footer" style="text-align: center">
               <?= Html::submitButton(Yii::t('app', 'Save'), ['class' =>'btn btn-success','name'=>'submit']) ?>
           </div>
       </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
