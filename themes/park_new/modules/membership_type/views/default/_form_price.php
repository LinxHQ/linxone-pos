<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\ListSetup;
use kartik\date\DatePicker;
use kartik\datetime\DateTimePicker;
use app\modules\membership_type\models\MembershipPrice;

/* @var $this yii\web\View */
/* @var $model app\modules\membership_type\models\MembershipPrice */
/* @var $form yii\widgets\ActiveForm */

$ListSetup = new ListSetup();
$modelMembership = new \app\modules\membership_type\models\MembershipType();
$arr_membershipType= $modelMembership->getDropDown();
if($model->membership_price_id){
    $model->getNextdate($model->membership_type_id,$model->price_start_date, $model->price_end_date, $model->membership_price_id);
}
?>
    <?php $form = ActiveForm::begin(['options' => ['id'=>"membership-price-form"]]); ?>
<div id="members-index" class="parkclub-wrapper parkclub-wrapper-search ">
    <div class="parkclub-rectangle parkclub-shadow">
       <div class="parkclub-invoice">
            <div class="parkclub-newm-left-title">
                <div class="parkclub-header-left">
                    <a href="<?php echo YII::$app->urlManager->createUrl(['membership_type/default/update','id'=>$model->membership_type_id]); ?>"><i class="glyphicon glyphicon-circle-arrow-left"></i></a>
                    <?php echo $label; ?>
                </div>
            </div>
           <div class="parkclub-newm" id="form_membership_type_price">
                <fieldset>
                    <label for=""><?php echo Yii::t('app','Type'); ?></label>
                    <?= $form->field($model, 'membership_type_id')->dropDownList($arr_membershipType,['disabled'=>'true'])->label(false) ?>
                    
                    <label for=""><?php echo Yii::t('app','Tax'); ?></label>
                    <?= $form->field($model, 'membership_price_tax')->dropDownList(ListSetup::getItemByList('Tax'),['onchange'=>'updatePrice();'])->label(false); ?>
                    
                    <label for=""><?php echo Yii::t('app','Price'); ?></label>
                    <?= $form->field($model, 'membership_price')->textInput(['onkeyup'=>'calculatePriceAfter(this.value)'])->label(false); ?>
                    
                    <label for=""><?php echo Yii::t('app','Price After Tax'); ?></label>
                    <?= $form->field($model, 'membership_price_after_tax')->textInput(['onkeyup'=>'calculatePrice(this.value)'])->label(false); ?>
                    
                    <label for=""><?php echo Yii::t('app','Start date'); ?></label>
                    <?php 
                    $start_date_default = date('Y-m-d');
                    if($model->price_start_date)
                        $start_date_default=$model->price_start_date;
                    echo $form->field($model, 'price_start_date')->widget(DatePicker::classname(), [
                    'options' => ['placeholder' => Yii::t('app','Start date'),'value'=>$start_date_default],
                    'type' => DateTimePicker::TYPE_COMPONENT_APPEND,
                    
                    'pluginOptions' => [
                            'autoclose' => true,
                            'format' => 'yyyy-mm-dd',
                       
                    ]
                    ])->label(false);
                ?>
                    
                    <label for=""><?php echo Yii::t('app','End date'); ?></label>
                    <?php 
                    $value_enddate="";
                            if($model->price_end_date && $model->price_end_date !="0000-00-00")
                                $value_enddate = $model->price_end_date;
                            echo $form->field($model, 'price_end_date')->widget(DatePicker::classname(), [
                            'options' => ['placeholder' => Yii::t('app','End date'),'value'=>$value_enddate,],

                                'type' => DateTimePicker::TYPE_COMPONENT_APPEND,
                            'pluginOptions' => [
                                    'autoclose' => true,
                                    'format' => 'yyyy-mm-dd',
                            ]
                            ])->label(false);
                        ?>
                <div id="error_date" style="color: #a94442;"></div>
                <input id="membership_price_id"   hidden="" value="<?php echo $model->membership_price_id;?>"/>
                </fieldset>
               </div>
               <div class="parkclub-footer" style="text-align: center">
                    <?= Html::button($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Save'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-success','onClick'=>'test()','id'=>'create-mbshiptype-price']) ?>
               </div>
            </div>
        </div>
</div>
    <?php ActiveForm::end(); ?>


<script>
    
    $( document ).ready(function() {
        var intall_data = '<?php echo Yii::$app->session['install-data']; ?>';
        var tour = '<?php echo Yii::$app->session['tour']; ?>';
        var tour_step = '<?php echo Yii::$app->session['tour-step']; ?>';
        if(intall_data==2 && tour==1){
            $('#membershipprice-membership_price').val('1000');
            $('#membershipprice-membership_price_after_tax').val('1100');
            $('#membershipprice-price_end_date').val('2017-12-30');
            tour_no_demo.restart();
            tour_no_demo.start();
            tour_no_demo.goTo(4);
        }
        if(tour_step=='<?php echo app\models\Config::TOUR_MEMBERSHIP_TYPE; ?>')
        {
            $('#membershipprice-membership_price').val('1000');
            $('#membershipprice-membership_price_after_tax').val('1100');
            $('#membershipprice-price_end_date').val('2017-12-30');
            tour_membership_type.restart();
            tour_membership_type.start();
            tour_membership_type.goTo(3);
        }
    });
    
    function test()
    {
        var membership_price_id=$('#membership_price_id').val();
        var start_date = $('#membershipprice-price_start_date').val();
        var end_date = $('#membershipprice-price_end_date').val();
        var membership_type_id = '<?php echo $model->membership_type_id;?>';
        
        //check date
        $.ajax({
            'type':'POST',
            'url':'<?php echo YII::$app->urlManager->createUrl('/membership_type/default/checkdate');  ?>',
            'data':{membership_price_id:membership_price_id,start_date:start_date,end_date:end_date,membership_type_id:membership_type_id},
            success:function(data){
                var responseJSON = jQuery.parseJSON(data);
                if(responseJSON.status == "Fail")
                {
                    $('#error_date').html(responseJSON.message);
                }
                else
                {
                    $('#error_date').html("");
                    $('#membership-price-form').submit();
                }
                
            }
        });
       
    }
    function parseDate(str) {
        var mdy = str.split('/');
        return new Date(mdy[2], mdy[1], mdy[0]);
    }
    
    function calculatePrice(price_after){
        if(price_after==''){
            $('#membershipprice-membership_price').val(0);
            return false;
        }
        var tax = $("#membershipprice-membership_price_tax option:selected").text();
        var price =(100*parseFloat(price_after))/(100+parseFloat(tax));
        $('#membershipprice-membership_price').val(price.toFixed(2));
    }
    
    function calculatePriceAfter(price){
        if(price==''){
            $('#membershipprice-membership_price_after_tax').val(0);
            return false;
        }
        var tax = $("#membershipprice-membership_price_tax option:selected").text();
        var price_after = parseFloat(price) + (parseFloat(price)*parseFloat(tax))/100;
        $('#membershipprice-membership_price_after_tax').val(price_after);
    }
    
    function updatePrice(){
        var tax = $("#membershipprice-membership_price_tax option:selected").text();
        var price = $('#membershipprice-membership_price').val();
        var price_after = 0;
        if(price!="")
            price_after = parseFloat(price) + (parseFloat(price)*parseFloat(tax))/100;
        $('#membershipprice-membership_price_after_tax').val(price_after);
    }
    
</script>
