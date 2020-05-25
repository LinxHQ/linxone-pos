<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use app\models\NextIds;
/* @var $this yii\web\View */
/* @var $model app\models\Booking */
/* @var $form yii\widgets\ActiveForm */

$Members = new \app\modules\members\models\Members();
$facility = new app\modules\facility\models\Facility();
$memberShip = new app\modules\members\models\Membership();
$price = new app\modules\facility\models\HistoryFacilityPrice();
$book = new \app\modules\booking\models\Booking;
$training = new app\modules\training\models\MemberTrainings();
$member_traner = new \app\modules\training\models\MemberTrainers();
$traning = false;$member_tranning_id=false;
if($model->traning_id){
    $traning = app\modules\training\models\MemberTrainings::findOne($model->traning_id);
    $member_tranning_id = $traning->member_id;
}
if($model->member_id)
    $trainer_id = $model->member_id;
$package_trainer = $training->getTranningByTrainerArray($trainer_id,$member_tranning_id);

if(isset($my_start_time))
{
    $my_start_time= date('H:i', strtotime($my_start_time));
    $model->book_startdate=$my_start_time;
    if($start < date('Y-m-d')){
        echo '<span class="glyphicon glyphicon-warning-sign" style="font-size: 14px; color:#a94442" ></span>
                    <label id="error_check_limit_booking_label" style="font-size: 14px;color:#a94442;" >'.Yii::t('app','You are not allowed to book at backdate').'</label>';
        exit();
    }else if(($start <= date('Y-m-d')) &&strtotime($my_start_time) < strtotime(date('H:i'))){
        echo '<span class="glyphicon glyphicon-warning-sign" style="font-size: 14px; color:#a94442" ></span>
                    <label id="error_check_limit_booking_label" style="font-size: 14px;color:#a94442;" >'.Yii::t('app','You are not allowed to book at backtime').'</label>';
        exit();
    }
    $model->facility_id = $_GET['faciliti_id'];
    $model->book_date = $_GET['start'];
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

if(isset($my_start_time))
{
    $my_start_time= date('H:i', strtotime($my_start_time));
    $model->book_startdate=$my_start_time;
}
else
    $my_start_time="";
if(isset($my_end_time))
{
    $my_end_time= date('H:i', strtotime($my_end_time));
    $model->book_enddate=$my_end_time;
}
else
    $my_end_time="";

//echo $my_end_time;
// Using a select2 widget inside a modal dialog
$member_name="";
$member_mobile="";
$nextId = new NextIds();
$confirmation_code = $nextId->getNextBookNumber(); 
if($model->member_id)
{
    $member_info = $Members->getMember($model->member_id);
    if($member_info)
    {
        $member_name = $member_info['member_name'];
        $member_mobile = $member_info['member_mobile'];
    }
}

?>

<div class="booking-form">

    <?php $form = ActiveForm::begin(['options'=>['onSubmit'=>'return check_data();']]); ?>
   
            <div class="booking1 row">
                <table class="table" style="text-align: left;">
                    <tr>
                        <td><h4><?php echo Yii::t('app','Confirmation Code');?>:</h4></td>
                        <td>
                            <span ><b><?php echo $confirmation_code;?></b> </span>
                        </td>
                    </tr>  
                    <tr>
                        <td><h4><?php echo Yii::t('app','Trainer');?>:</h4></td>
                        <td>
                            <span id="member_name">
                            <?php 
                            
                            echo $form->field($model, 'member_id')->widget(Select2::classname(), [
                                'data' => $Members->getDataDropdown(1),
                                'options' => ['placeholder' => Yii::t('app','Select trainer ...'),'onchange'=>'loadMember(this.value)',
                                    'value'=>$trainer_id],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                    
                                ],
                            ])->label(false);
                            ?>
                            </span>
                        </td>
                    </tr>  
                    <tr>
                        <td><h4><?php echo Yii::t('app','Member');?>:</h4></td>
                        <td>
                            <div class="form-group">
                            <?php 
                                echo yii\bootstrap\Html::dropDownList('member_tr_id',(($traning) ? $traning->member_id : ''), $member_traner->getMemberByTrainer($trainer_id,true, \app\modules\members\models\Members::STATUS_MEMBER_ACTIVE), 
                                        ['placeholder' => 'Select states ...','onchange'=>'loadDataPackage()','class'=>'form-control',
                                            'id'=>'member_tr_id']);
                            ?>
                                <div class="member-help-block"></div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><h4><?php echo Yii::t('app','Traning Package');?>:</h4></td>
                        <td>
                            <?php 
                            echo $form->field($model, 'traning_id')->dropDownList($package_trainer,
                                    ['placeholder' => 'Select trainer ...','onchange'=>'loadMember(this.value)']
                                )->label(false);
                            ?>
                           
                        </td>
                    </tr>
                    <tr>
                        <td><h4><?php echo Yii::t('app','Date');?>:</h4></td>
                        <td>
                            <?php        
                                $model->book_date = ($model->book_date) ? $model->book_date : $start;
                                echo $form->field($model, 'book_date')->widget(DatePicker::classname(), [
                                    'options' => ['value' => $model->book_date],
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
                            <?= $form->field($model, 'book_startdate')->dropDownList($book->getStartTime(false,$model->book_id,$model->book_date,$model->member_id),['onchange'=>'loadEndTime()'])->label(false); ?>
                        </td>
                    </tr>
                    <tr>
                        <td><h4><?php echo Yii::t('app','End time');?>:</h4></td>
                        <td>
                            <?= $form->field($model, 'book_enddate')->dropDownList($book->getEndTime($model->facility_id,$model->book_startdate,$model->book_id,$model->book_date,$model->member_id))->label(false); ?>
                        </td>
                    </tr>
                    <tr>
                        <td><h4><?php echo Yii::t('app','Note');?>:</h4></td>
                        <td>
                            <?= $form->field($model, 'book_notes')->textarea()->label(false); ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: right;"></td>
                        <td></td>
                    </tr>
                    </table>
            </div>
            <div></div>
            <div class="parkclub-footer" style="text-align: center">
                <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>

    <?php ActiveForm::end(); ?>

</div>

<script type="text/javascript">
    $(document).ready(function(){  
        $.unblockUI();
        var trainer_id = '<?php echo $trainer_id; ?>';
        var book_starttime = '<?php echo $my_start_time; ?>';
        if(trainer_id>0 && book_starttime!="")
        {
            loadDataPackage(trainer_id);
            $.fn.modal.Constructor.prototype.enforceFocus = function() {};
        }
        
        var active_new = '<?php echo $my_start_time; ?>';
        if(active_new != '')
            loadDataPackage();

    });
   
    function loadMember(trainer_id){
        $.ajax({
            'type':'POST',
            'url':'<?php echo YII::$app->urlManager->createUrl('/trainer_booking/default/loadmember');  ?>',
            'data':{trainer_id:trainer_id},
            success:function(data){
                $('#member_tr_id').html(data);
                loadDataPackage();
            }
        });
    }
    
    function loadDataPackage(){
        var member_id = $('#member_tr_id').val();
        var trainer_id = $('#booking-member_id').val();
        $.ajax({
            'type':'POST',
            'url':'<?php echo YII::$app->urlManager->createUrl('/trainer_booking/default/load-package');  ?>',
            'data':{member_id:member_id,trainer_id:trainer_id},
            success:function(data){
                $('#booking-traning_id').html(data);
            }
        });
    }
    

    
    function loadStarttime(){
        var book_date = $('#booking-book_date').val();
        var trainer_id = $('#booking-member_id').val();
        $.ajax({
            'type':'POST',
            'url':'<?php echo YII::$app->urlManager->createUrl('/trainer_booking/default/loadstarttime');  ?>',
            'data':{trainer_id:trainer_id,book_date:book_date},
            success:function(data){
                $('#booking-book_startdate').html(data);
                loadEndTime()();
            }
        });
        
    }
    
    function loadEndTime(){
        var trainer_id = $('#booking-member_id').val();
        var book_date = $('#booking-book_date').val();
        var starttime = $('#booking-book_startdate').val();
        $.ajax({
            'type':'POST',
            'url':'<?php echo YII::$app->urlManager->createUrl('/trainer_booking/default/loadendstart');  ?>',
            'data':{trainer_id:trainer_id,starttime:starttime,book_date:book_date},
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
    function check_data(){
        var booking_member_id = $('#member_tr_id').val();
        if(booking_member_id=="" || booking_member_id==null){
            $('.member-help-block').css('color','#a94442');
            $('.member-help-block').html("Member cannot be blank.");
            return false;
        }
//        var booking-price_id = $('#booking-price_id option:selected').val();
//        if(booking-price_id==""){
//            $('.field-booking-member_id.required .help-block').css('color','#a94442');
//            $('.field-booking-member_id.required .help-block').html("Member cannot be blank.");
//            return false;
//        }
        return true;
    }
    </script>