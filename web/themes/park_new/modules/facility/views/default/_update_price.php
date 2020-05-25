<?php 
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
$ListSetup = new app\models\ListSetup();
?>
<div class="limit-form">
    <?php $form = ActiveForm::begin(['options'=>['onSubmit'=>'return check_data();','id'=>'frm-update-price']]); ?>
        <div id="members-index" class="parkclub-wrapper parkclub-wrapper-search ">
            <div class="parkclub-rectangle parkclub-shadow">
               <div class="parkclub-invoice">
                    <div class="parkclub-newm-left-title">
                        <div class="parkclub-header-left">
							<a href="<?php echo YII::$app->urlManager->createUrl(['facility/default/view','id'=>$model->faculity_id]); ?>">
								<i class="glyphicon glyphicon-circle-arrow-left"></i>
                            </a>
							<?php echo Yii::t('app', 'Update Price'); ?>
                        </div>
                    </div>
                    <div class="parkclub-newm ">
                        <fieldset>
                            <label for=""><?php echo Yii::t('app', 'Membership type'); ?></label>
                            <?= $form->field($model, 'membership_type_id')->dropDownList($membershipTypeDropdow)->label(false); ?>
                            <label for=""><?php echo Yii::t('app', 'Name'); ?></label>
                            <?= $form->field($model, 'facility_price_name')->textInput()->label(false); ?>
                            <label for=""><?php echo Yii::t('app', 'Tax'); ?></label>
                            <?= $form->field($model, 'facility_price_tax')->dropDownList(\app\models\ListSetup::getItemByList('Tax'),['onchange'=>'updatePrice()'])->label(false)->label(false); ?>
                            <label for=""><?php echo Yii::t('app', 'Price'); ?></label>
                            <?= $form->field($model, 'facility_price')->textInput(['onkeyup'=>'calculatePriceAfter(this.value)'])->label(false); ?>
                            <label for=""><?php echo Yii::t('app', 'Price After Tax'); ?></label>
                            <?= $form->field($model, 'facility_price_after_tax')->textInput(['onkeyup'=>'calculatePrice(this.value)'])->label(false); ?>
                            <label for=""><?php echo Yii::t('app', 'Start date'); ?></label>
                            <?php
                                echo DatePicker::widget([
                                  'name' => 'HistoryFacilityPrice[facility_startdate]',
                                  'id' => 'statrs',
                                  'value' => $ListSetup->getDisplayDateSql($model->facility_startdate),
                                  'type' => DatePicker::TYPE_COMPONENT_APPEND,
                                  'options' => ['placeholder' => 'End date ...','onchange'=>'exit_add();'],
                                  'removeButton' => false,
                                  'pluginOptions' => [
                                      'autoclose'=>true,
                                      'format' => 'yyyy-mm-dd',
                                  ]
                              ]);
                            ?>
                            <label for=""><?php echo Yii::t('app', 'End date'); ?></label>
                            <?php
                                
                                echo DatePicker::widget([
                                  'name' => 'HistoryFacilityPrice[faclity_enddate]',
                                  'id' => 'enddate',
                                  'value' => ($model->faclity_enddate) ? $ListSetup->getDisplayDateSql($model->faclity_enddate) : '',
                                  'type' => DatePicker::TYPE_COMPONENT_APPEND,
                                  'options' => ['placeholder' => 'End date ...','onchange'=>'exit_add();'],
                                  'removeButton' => false,
                                  'pluginOptions' => [
                                      'autoclose'=>true,
                                      'format' => 'yyyy-mm-dd',
                                  ]
                              ]);
                            ?>
                        </fieldset>
                    </div>
                   <br>
                   <div class="parkclub-footer" style="text-align: center">
                       <?= Html::submitButton( Yii::t('app', 'Update'), ['class' =>'btn btn-primary']) ?>
                   </div>
               </div>
            </div>
        </div>
    <?php ActiveForm::end(); ?>

</div>
<script type="text/javascript">
    function exit_add()
    {
        var membership_type_id = $('#historyfacilityprice-membership_type_id').val();
        var start_date = $('#statrs').val();
        var end_date = $('#check_date').val();
        
        var facility_id="<?php echo $model->history_facility_id;?>";
        
        //check date
        $.ajax({
            'type':'POST',
            'url':'<?php echo YII::$app->urlManager->createUrl('/facility/default/checkdate');  ?>',
            'data':{membership_type_id:membership_type_id,start_date:start_date,end_date:end_date,facility_id:facility_id},
            success:function(data){
                var responseJSON = jQuery.parseJSON(data);
                if(responseJSON.status == "Fail")
                {
                    $('#error_date').html(responseJSON.message);
                    $('#check_value').val(1);
                    
                }
                else
                {
                     $('#error_date').html("");
                     $('#check_value').val(0);
                }
                
            }
        });
    }
    function check_data(){
        var check_value = $('#check_value').val();
        if(check_value==1)
            return false;
        return true;
    }
    
    function calculatePrice(price_after){
        if(price_after==''){
            $('#historyfacilityprice-facility_price').val(0);
            return false;
        }
        var tax = $('#historyfacilityprice-facility_price_tax option:selected').text();
        var price =(100*parseFloat(price_after))/(100+parseFloat(tax));
        $('#historyfacilityprice-facility_price').val(price.toFixed(0));
    }
    
    function calculatePriceAfter(price){
        if(price==''){
            $('#historyfacilityprice-facility_price_after_tax').val(0);
            return false;
        }
        var tax = $('#historyfacilityprice-facility_price_tax option:selected').text();
        var price_after = parseFloat(price) + (parseFloat(price)*parseFloat(tax))/100;
        $('#historyfacilityprice-facility_price_after_tax').val(price_after.toFixed(0));
    }
    
    function updatePrice(){
        var tax = $('#historyfacilityprice-facility_price_tax option:selected').text();
        var price = $('#historyfacilityprice-facility_price').val();
        var price_after = 0;
        if(price!="")
            var price_after = parseFloat(price) + (parseFloat(price)*parseFloat(tax))/100;
        $('#historyfacilityprice-facility_price_after_tax').val(price_after);
    }
</script>