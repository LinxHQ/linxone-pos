<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\ListSetup;
use kartik\date\DatePicker;
use kartik\datetime\DateTimePicker;
use app\modules\revenue_type\models\RevenueItem;

$ListSetup = new ListSetup();

/* @var $this yii\web\View */
/* @var $model app\modules\revenue_type\models\RevenueItem */
/* @var $form yii\widgets\ActiveForm */
?>
<?php $form = ActiveForm::begin(); ?>
<div id="members-index" class="parkclub-wrapper parkclub-wrapper-search ">
    <div class="parkclub-rectangle parkclub-shadow">
       <div class="parkclub-invoice">
            <div class="parkclub-newm-left-title">
                <div class="parkclub-header-left">
                    <a href="<?php echo YII::$app->urlManager->createUrl(['revenue_type/default/update','id'=>$model->revenue_id]); ?>"><i class="glyphicon glyphicon-circle-arrow-left"></i></a>
                    <?php echo Yii::t('app',"Revenue Item"); ?>
                </div>
            </div>
           <div class="parkclub-newm ">
                <fieldset>
                   
                    <?= $form->field($model, 'revenue_item_name')->textInput(['maxlength' => true]) ?>
                    <label for=""><?php echo Yii::t('app','Tax'); ?></label>
                    <?= $form->field($model, 'revenue_item_tax')->dropDownList(ListSetup::getItemByList('Tax'),['onchange'=>'updatePrice()'])->label(false); ?>
                    
                    <label for=""><?php echo Yii::t('app','Price'); ?></label>
                    <?= $form->field($model, 'revenue_item_price')->textInput(['onkeyup'=>'calculatePriceAfter(this.value)'])->label(false); ?>
                    <label for=""><?php echo Yii::t('app','Price After Tax'); ?></label>
                    <?= $form->field($model, 'revenue_item_price_after_tax')->textInput(['onkeyup'=>'calculatePrice(this.value)'])->label(false); ?>
                  
                    <?= $form->field($model, 'revenue_item_description')->textarea(['maxlength' => true]) ?>
                    <label for=""><?php echo Yii::t('app','Status'); ?></label>
                    <?= $form->field($model, 'revenue_item_status')->dropDownList(ListSetup::getItemByList('status'))->label(false) ?>
                    
                </fieldset>
                   <div class="parkclub-footer" style="text-align: center">
                        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                   </div>
               </div>
            </div>
        </div>
</div>
<?php ActiveForm::end(); ?>
<script>
    
    
    function parseDate(str) {
        var mdy = str.split('/');
        return new Date(mdy[2], mdy[1], mdy[0]);
    }
    
    function calculatePrice(price_after){
        if(price_after==''){
            $('#revenueitem-revenue_item_price').val(0);
            return false;
        }
        var tax = $('#revenueitem-revenue_item_tax option:selected').text();
        var price =(100*parseFloat(price_after))/(100+parseFloat(tax));
        $('#revenueitem-revenue_item_price').val(price.toFixed(2));
    }
    
    function calculatePriceAfter(price){
        if(price==''){
            $('#revenueitem-revenue_item_price_after_tax').val(0);
            return false;
        }
        var tax = $('#revenueitem-revenue_item_tax option:selected').text();
        var price_after = parseFloat(price) + (parseFloat(price)*parseFloat(tax))/100;
        $('#revenueitem-revenue_item_price_after_tax').val(price_after);
    }
    function updatePrice(){
        var tax = $('#revenueitem-revenue_item_tax option:selected').text();
        var price = $('#revenueitem-revenue_item_price').val();
        calculatePriceAfter(price);
    }
</script>






