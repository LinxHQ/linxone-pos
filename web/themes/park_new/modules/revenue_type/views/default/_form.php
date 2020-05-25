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
                            
                            <label for=""><?php echo Yii::t('app', 'Revenue Type'); ?></label>
                            <?= $form->field($model, 'entry_type')->dropDownList(ListSetup::getItemByList('revenue'))->label(false); ?>
                            
                        </fieldset>
                   </div>
                    
                   <div class="parkclub-footer" style="text-align: center">
                       <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-success']) ?>
                   </div>
               </div>
            </div>
        </div>
    <div id="members-index" class="parkclub-wrapper parkclub-wrapper-search ">
    <div class="parkclub-rectangle parkclub-shadow">
       <div class="parkclub-invoice">
            <div class="parkclub-newm-left-title">
                <div class="parkclub-header-left">
                    <?php echo Yii::t('app', 'Revenue Item'); ?>
                </div>
            </div>
           <div class="parkclub-rectangle-content">
               <table style="margin-bottom: 0px;" id="table_facility">
                    <tr>
                       
                        <th><?php echo Yii::t('app', 'Name'); ?></th>
                        <th><?php echo Yii::t('app', 'Price'); ?></th>
                        <th><?php echo Yii::t('app', 'Tax'); ?></th>
                        <th><?php echo Yii::t('app', 'Price After Tax'); ?></th>
                        <th><?php echo Yii::t('app', 'Status'); ?></th>
                        <th><?php echo Yii::t('app', 'Actions'); ?></th>
                    </tr>
                    <?php 
                 
                    $revenue = RevenueItem::find()->all();
                  
                    foreach ($revenue as $revenue_item) { 
                  
                    if($revenue_item['revenue_id']==$model->revenue_id){
                    ?>
                    <tr >
                    
                        <td><?php echo $revenue_item['revenue_item_name']; ?></td>
                        <td><?php echo $revenue_item['revenue_item_price']; ?></td>
                        <td><?php $list_setup = new \app\models\ListSetup();
                                echo ListSetup::getItemByList('Tax')[$revenue_item['revenue_item_tax']];
                        
                        ?></td>
                        <td><?php echo $revenue_item['revenue_item_price_after_tax']; ?></td>
                        <td><?php 
                                $list_setup = new \app\models\ListSetup();
                                echo ListSetup::getItemByList('status')[$revenue_item['revenue_item_status']];
                                
                        ?></td>
                        <td><a href="<?php echo Yii::$app->urlManager->createUrl("/revenue_type/revenueitem/update?id=".$revenue_item['revenue_item_id']); ?>" ><i class="glyphicon glyphicon-pencil" ></i></a>
                        
                            
                        </td>
                    </tr>
                    <?php 
                    }
                    } ?>
                </table>
           </div>
       </div>
        <div class="parkclub-footer">
            <?= Html::a(Yii::t('app', 'Add Revenue Item'),Yii::$app->urlManager->createUrl('revenue_type/revenueitem/create?id='.$model->revenue_id), ['class' =>'btn btn-primary']) ?>
        </div>
    </div>
</div>

    <?php ActiveForm::end(); ?>
    

</div>
