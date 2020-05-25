<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\Booking */
/* @var $form yii\widgets\ActiveForm */

$Members = new \app\modules\members\models\Members();
$facility = new app\modules\facility\models\Facility();
$memberShip = new app\modules\members\models\Membership();
$price = new app\modules\facility\models\HistoryFacilityPrice();
$book = new \app\modules\booking\models\Booking;
$invoice = new \app\modules\invoice\models\invoice();
$ListSetup = new app\models\ListSetup();

//$history = new \app\modules\history\models\History();
//$history->addHistory(1, "booking", "booking", "add", "test");
$date_start = (isset($_GET['start']) ? $_GET['start'] :  date('Y-m-d'));
if(isset($my_start_time))
{
    $my_start_time= date('H:i', strtotime($my_start_time));
    $model->book_startdate=$my_start_time;
    if($start < date('Y-m-d')){
        echo '<span class="glyphicon glyphicon-warning-sign" style="font-size: 14px; color:#a94442" ></span>
                    <label id="error_check_limit_booking_label" style="font-size: 14px;color:#a94442;" >'.Yii::t('app','You are not allowed to book at backdate').'</label>';
        exit();
    }elseif(($start <= date('Y-m-d')) && strtotime($my_start_time) < strtotime(date('H:i'))){
        echo '<span class="glyphicon glyphicon-warning-sign" style="font-size: 14px; color:#a94442" ></span>
                    <label id="error_check_limit_booking_label" style="font-size: 14px;color:#a94442;" >'.Yii::t('app','You are not allowed to book at backtime').'</label>';
        exit();
    }
    if($book->isLimitBookWeek($date_start)){
        echo YII::t('app', 'You are allowed to book from this Saturday until the next Sunday.');
        exit();
    }
//    echo $book->isLimitBookWeek($date_start);
}
else
    $my_start_time="";

if(isset($my_end_time))
{
    $my_end_time = strtotime($my_end_time. " +30 minute");
    $my_end_time = strftime("%H:%M",$my_end_time);
    $model->book_enddate=$my_end_time;
}
else
    $my_end_time="";
$faciliti_id=isset($faciliti_id)?$faciliti_id:"";
if(isset($faciliti_id) && $faciliti_id>0)
    $model->facility_id=$faciliti_id;
//echo $my_end_time;
// Using a select2 widget inside a modal dialog
$member_name="";
$member_mobile="";
if($model->member_id)
{
    $member_info = $Members->getMember($model->member_id);
    if($member_info)
    {
        $member_name = $Members->getMemberFullName($member_info['member_id']);
        $member_mobile = $member_info['member_mobile'];
    }
}
$width = "";
if(isset($action))
    $width = ';width:90%';
?>
<style>
    #modal-select .modal-dialog.modal-lg .modal-body{
        width: 100%;
    }
</style>

<div class="booking-form">

    <?php $form = ActiveForm::begin(
                ['options'=>['onSubmit'=>'return check_data();',
                    'id'=>'frm-booking']]
            ); ?>
            
            <div class="booking1 row">
                <div style="color: #a94442; display: none;" id="error_check_limit_booking">
                    <span class="glyphicon glyphicon-warning-sign" style="font-size: 14px; color:#a94442" ></span>
                    <label id="error_check_limit_booking_label" class="font-size: 12px;" ></label>
                </div>
                <input type="hidden" value="0" id="error_booking_data"/>
                <table class="table" style="text-align: left <?php echo $width ?> ">
                    <tr>
                        <td><h4><?php echo Yii::t('app', 'Barcode'); ?>:</h4></td>
                        <td>
                            <?php 
                            $dataMemberNo = $Members->getDataDropdownBarcode() + $Members->getDataDropdownGuest();
                            echo $form->field($model, 'member_id')->widget(Select2::classname(), [
                                'data' => $dataMemberNo,
                                'options' => ['placeholder' => Yii::t('app', 'Select barcode'),'onchange'=>'loadMember(this.value)'],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                    
                                ],
                            ])->label(false);
                            ?>
                            
                        </td>
                        
                    </tr>
                    <tr>
                        <td><h4><?php echo Yii::t('app','Member');?>:</h4></td>
                        <td>
                            <span id="member_name">
                            <?php 
                             echo $member_name;
//                            echo $form->field($model, 'member_id')->widget(Select2::classname(), [
//                                'data' => $Members->getDataDropdown(),
//                                'options' => ['placeholder' => 'Select member ...','onchange'=>'loadMemberShip(this.value)'],
//                                'pluginOptions' => [
//                                    'allowClear' => true,
//                                    
//                                ],
//                            ])->label(false);
                            ?>
                            </span>
                        </td>
                        
                    <tr>
                        <td><h4><?php echo Yii::t('app','Mobile'); ?>:</h4></td>
                        <td>
                            <span id="member_mobile"><?php  echo $member_mobile;?></span>
                            
                        </td>
                        
                    </tr>
<!--                    <tr>
                        <td><h4>Membership:</h4></td>
                        <td>
                            <?= $form->field($model, 'membership_id')->dropDownList($memberShip->getDataDropdown(),['onchange'=>'loadPrice()'])->label(false); ?>
                        </td>
                    </tr>-->
                    <tr>
                        <td><h4><?php echo YII::t('app','Facility');?>:</h4></td>
                        <td><?= $form->field($model, 'facility_id')->dropDownList($facility->getDataDropdown(),['onchange'=>'loadDataByFacility()'])->label(false); ?>
                        </td>
                    </tr>
                    <tr>
                        <td><h4><?php echo Yii::t('app','Price') ?>:</h4></td>
                        <td><div class="form-group" id="book_price"><?php
                            $invoice_data = $invoice->find()->where(['invoice_type'=>'booking','invoice_type_id'=>$model->book_id])->one();
                            if($invoice_data)
                                echo '(VNĐ) '.$ListSetup->getDisplayPrice ($invoice->getSubtotalInvocie($invoice_data->invoice_id)); 
                            else
                                echo 0;
                        ?></div>
                        </td>
                    </tr>

                    <tr>
                        <td><h4><?php echo Yii::t('app','Date');?></h4></td>
                        <td>
                            <?php                     
                                
                                echo $form->field($model, 'book_date')->widget(DatePicker::classname(), [
                                    'options' => ['value' => $date_start,'readonly'=>true],
                                    'pluginOptions' => [
                                        'autoclose'=>true,
                                        'format' => 'yyyy-m-dd',
                                        'startDate'=> date('Y-m-d')
                                    ],
                                    'type' => DatePicker::TYPE_COMPONENT_APPEND,
                                    'pluginEvents' => [
                                        'change' => 'function() { loadStarttime(); }',
                                        ],
                                ])->label(false);     
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td><h4><?php echo Yii::t('app','Start time');?>:</h4></td>
                        <td>
                            <?php $model->book_date = (($model->book_date) ? $model->book_date : $date_start); ?>
                            <?= $form->field($model, 'book_startdate')->dropDownList($book->getStartTime($model->facility_id,$model->book_id,$model->book_date),['onchange'=>'loadEndTime(this.value)'])->label(false); ?>
                        </td>
                    </tr>
                    <tr>
                        <td><h4><?php echo YII::t('app','End time');?>:</h4></td>
                        <td>
                            <?= $form->field($model, 'book_enddate')->dropDownList($book->getEndTime($model->facility_id,$model->book_startdate,$model->book_id,$model->book_date))->label(false); ?>
                        </td>
                    </tr>
                    <tr>
                        <td><h4><?php echo YII::t('app','Note');?>:</h4></td>
                        <td>
                            <?= $form->field($model, 'book_notes')->textarea()->label(false); ?>
                        </td>
                    </tr>
                    </table>

            </div>
            <div></div>
            <div class="parkclub-footer" style="text-align: center">
                <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-success','id'=>'submit']) ?>
            </div>

    <?php ActiveForm::end(); ?>

</div>

<script type="text/javascript">
    $(document).ready(function(){  
        $.unblockUI();
        var facility = '<?php echo $faciliti_id; ?>';
        var book_starttime = '<?php echo $my_start_time; ?>';
        
        if(facility>0)
        {
            loadDataByFacility(facility,1);
            $.fn.modal.Constructor.prototype.enforceFocus = function() {};
        }
        
        var tour_step = '<?php echo Yii::$app->session['tour-step']; ?>';
        if(tour_step=='<?php echo app\models\Config::TOUR_BOOKING; ?>')
        {
//            tour_booking1.restart();
            tour_booking1.start();
    
        }            
    });
        
    function loadMemberShip(member_id){
        
        $.ajax({
            'type':'POST',
            'url':'<?php echo YII::$app->urlManager->createUrl('/booking/default/loadmembership');  ?>',
            'data':{member_id:member_id},
            success:function(data){
                $('#booking-membership_id').html(data);
            }
        });
    }
    function loadMember(member_id){
        $.ajax({
            'type':'POST',
            'url':'<?php echo YII::$app->urlManager->createUrl('/booking/default/loadmember');  ?>',
            'data':{member_id:member_id},
            success:function(data){
                var responseJSON = jQuery.parseJSON(data);

                $('#member_name').html(responseJSON.member_name);
                $('#member_mobile').html(responseJSON.member_mobile);
                        loadDataByFacility(0,1);
            }
        });
        check_limit_booking();
    }
    
    function loadDataByFacility(facility_id,load_default){
        var book_date = $('#booking-book_date').val();
        var facility_id = $('#booking-facility_id').val();
        var member_id = $('#booking-member_id').val();
       $.ajax({
            'type':'POST',
            'url':'<?php echo YII::$app->urlManager->createUrl('/booking/default/load_price');  ?>',
            'data':{facility_id:facility_id,book_date:book_date,member_id:member_id},
            success:function(data){
                $('#book_price').html(data);
            }
        });
        if(load_default != 1)
        {
            loadStarttime();
            
        }
        check_limit_booking();
    }
    
    function loadStarttime(){
        var book_date = $('#booking-book_date').val();
        var facility_id = $('#booking-facility_id').val();
        $.ajax({
            'type':'POST',
            'url':'<?php echo YII::$app->urlManager->createUrl('/booking/default/loadstarttime');  ?>',
            'data':{facility_id:facility_id,book_date:book_date},
            success:function(data){
                $('#booking-book_startdate').html(data);
            }
        });
        check_limit_booking();
        
    }
    
    function loadEndTime(starttime){
        var facility_id = $('#booking-facility_id').val();
        
        var book_date = $('#booking-book_date').val();
        $.ajax({
            'type':'POST',
            'url':'<?php echo YII::$app->urlManager->createUrl('/booking/default/loadendstart');  ?>',
            'data':{facility_id:facility_id,starttime:starttime,book_date:book_date},
            success:function(data){
                $('#booking-book_enddate').html(data);
            }
        });
    }
    function loadPrice(){
//        var member_id = $('#booking-member_id').val();
        var facility_id = $('#booking-facility_id').val();
//        var membership_id = $('#booking-membership_id').val();
        $.ajax({
            'type':'POST',
            'url':'<?php echo YII::$app->urlManager->createUrl('/booking/default/loadprice');  ?>',
            'data':{facility_id:facility_id},
            success:function(data){
                $('#booking-price_id').html("");
                $('#booking-price_id').html(data);
            }
        });
    }
    
    function check_limit_booking(){
        var member_id = $('#booking-member_id').val();
        var facility_id = $('#booking-facility_id').val();
        var book_date = $('#booking-book_date').val();
        $.ajax({
            type:'POST',
            url:'<?php echo yii::$app->urlManager->createUrl('booking/default/check_limit_booking'); ?>',
            data:{member_id:member_id,facility_id:facility_id,book_date:book_date},
            success:function(data){
                $('#error_booking_data').val(data);
                if(data==1){
                    $('#error_check_limit_booking').show();
                    $('#error_check_limit_booking_label').html("This booking isnot allowed. As your daily bookings are now equal to your membership booking limit.");  
                }
                else if(data==2){
                    $('#error_check_limit_booking').show();
                    $('#error_check_limit_booking_label').html("This booking isnot allowed. As your weekly bookings are now equal to your membership booking limit.");   
                }
                else if(data==3){
                    $('#error_check_limit_booking').show();
                    $('#error_check_limit_booking_label').html("This booking isnot allowed. As your monthly bookings are now equal to your membership booking limit.");  
                }
                else{
                    $('#error_check_limit_booking').hide();
                    $('#error_check_limit_booking_label').html("");  
                }
            }
        });
    }
    
    function check_data(){
        var booking_member_id = $('#booking-member_id').val();
        var error_booking_limit = $('#error_booking_data').val();
        if(booking_member_id==""){
            $('.field-booking-member_id.required .help-block').css('color','#a94442');
            $('.field-booking-member_id.required .help-block').html("Member cannot be blank.");
            return false;
        }
        if(error_booking_limit!=0)
            return false
        return true;
    }
</script>