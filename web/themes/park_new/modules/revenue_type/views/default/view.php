<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;
use app\modules\revenue_type\models\RevenueItem;

/* @var $this yii\web\View */
/* @var $model app\modules\revenue_type\models\Revenue */
//Check permission 
$m = 'membership_type';
$BasicPermission = new \app\modules\permission\models\BasicPermission();
$canList = $BasicPermission->checkModules($m, 'list');
$canAdd = $BasicPermission->checkModules($m, 'add');
$canEdit = $BasicPermission->checkModules($m, 'update');
$canDelete = $BasicPermission->checkModules($m, 'delete');
$canView = $BasicPermission->checkModules($m, 'view');

if(!$canView){
    echo "You don't have permission with this action.";
    return false;
}
//End check permission
?>
<?php $form = ActiveForm::begin(); ?>
<div id="members-index" class="parkclub-wrapper parkclub-wrapper-search ">
            <div class="parkclub-rectangle parkclub-shadow">
               <div class="parkclub-invoice">
                    <div class="parkclub-newm-left-title">
                        <div class="parkclub-header-left">
                            <i class="glyphicon glyphicon-circle-arrow-left"></i>
                            <?php echo Yii::t('app', 'Revenue'); ?>
                        </div>
                    </div>
                   <div class="parkclub-newm ">
                        <fieldset>
                            <label for=""><?php echo Yii::t('app', 'Name'); ?></label>
                            <?= $form->field($model, 'revenue_name')->textInput(['maxlength' => true])->label(false); ?>
                            
                            <label for=""><?php echo Yii::t('app', 'Description'); ?></label>
                            <?= $form->field($model, 'revenue_description')->textarea(['rows' => 6])->label(false); ?>
                            
                            
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
                    Revenue Item
                </div>
            </div>
           <div class="parkclub-rectangle-content">
               <table style="margin-bottom: 0px;" id="table_facility">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Tax</th>
                        <th>Price After Tax</th>
                        <th>Actions</th>
                    </tr>
                    <?php 
                    $stt_limit = 0;
                    $revenue = RevenueItem::find()->all();
                  
                    foreach ($revenue as $revenue_item) { 
                    $stt_limit++;    
                    if($revenue_item['revenue_id']==$model->revenue_id){
                    ?>
                    <tr id='limit_<?php echo $stt_limit; ?>'>
                        <td><?php echo $stt_limit; ?></td>
                        <td><?php echo $revenue_item['revenue_item_name']; ?></td>
                        <td><?php echo $revenue_item['revenue_item_price']; ?></td>
                        <td><?php echo $revenue_item['revenue_item_tax']; ?></td>
                        <td><?php echo $revenue_item['revenue_item_price_after_tax']; ?></td>
                        <td><a href="<?php echo Yii::$app->urlManager->createUrl("/revenue_type/revenueitem/update?id=".$revenue_item['revenue_id']); ?>" ><i class="glyphicon glyphicon-pencil" ></i></a>
                            
                            
                        </td>
                    </tr>
                    <?php 
                    }
                    } ?>
                </table>
           </div>
       </div>
        <div class="parkclub-footer">
            <button onclick="addLimit(); return false;" type="button" class="btn btn-primary" >Add Revenue Item</button>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
