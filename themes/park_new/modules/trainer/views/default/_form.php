<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use kartik\password\PasswordInput;
use app\models\ListSetup;
use app\modules\members\models\Membership;
use app\modules\membership_type\models\MembershipType;
use app\models\NextIds;
use kartik\select2\Select2;


/* @var $this yii\web\View */
/* @var $model app\modules\members\models\Members */
/* @var $form yii\widgets\ActiveForm */
$ListSetup = new ListSetup();
$MembershipType= new MembershipType();
$arr_membershipType= $MembershipType->getDropDown();
$MembershipType= new Membership();
$modelMemberShip= new Membership();
$nextManage = new NextIds();
$card_no = $nextManage->getDisplayCardNo();
?>
<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data','onSubmit'=>"return check_data(); ",'name'=>'w0[]']]);  ?>
<div class="parkclub-new-member-wap">
    <div class="parkclub-new-member">
        <div class="parkclub-newm-left parkclub-shadow" style="min-height: 600px;">
            <div class="parkclub-newm-left-title">
                <?php echo Yii::t('app', 'Add Trainer'); ?>
            </div>
            <div class="view_picture_after" id="view_picture_after" style="display: block;">
                <img id="member_img" src="<?php echo Yii::$app->urlManager->baseUrl; ?>/image/park_new/unknown.png" />
            </div>
            <fieldset>
                <label for=""><?php echo Yii::t('app', 'First Name'); ?></label>
                <input id="member_first_name" type="text" value="" name="Member[first_name]" />
                <div id="error_member_first_name" style="color:#a94442" class="parkclub-newm-error"  hidden=""><?php echo Yii::t('app', 'First name can not be blank.'); ?></div>
                
                <label for=""><?php echo Yii::t('app', 'Surname'); ?></label>
                <input id="member_surname" type="text" value="" name="Member[surname]"  />
                <div id="error_member_surname" style="color:#a94442" class="parkclub-newm-error"  hidden=""><?php echo Yii::t('app', 'Surname can not be blank.'); ?></div>
                
                <label for=""><?php echo Yii::t('app', 'Given Name'); ?></label>
                <input type="text" value="" name="Member[given_name]" />
                    
                <label for=""><?php echo Yii::t('app', 'Mobile'); ?></label>
                <input id="member_mobile" onchange="checkMember(this.value,'member_mobile')" onkeyup="checkMember(this.value,'member_mobile')" type="text" value="" class="member_mobile[]" name="Member[member_mobile]"   />
                <div hidden="" style="color:#a94442" id="error_member_mobile"><?php echo Yii::t('app', 'Mobile can not be blank'); ?></div>
                <input type="hidden" value="1" id="already_mobile" />
                <input type="hidden" value="1" id="already_id" />
                    
            </fieldset>
        </div>
        <div class="parkclub-newm-right parkclub-shadow" style="min-height: 600px;">
            <fieldset>
                
                <label for=""><?php echo Yii::t('app', 'Address'); ?></label>
                <input id="member_address" type="text" value="" name="Member[member_address]"  />
                <div id="error_member_address" style="color:#a94442" class="parkclub-newm-error"  hidden=""><?php echo Yii::t('app', 'Adress can not be blank'); ?></div>
                    
                <label for=""><?php echo Yii::t('app', 'Phone'); ?></label>
                <input type="text" value="" id="member_phone" name="Member[member_phone]" />
                <div hidden="" style="color:#a94442" id="error_member_phone"><?php echo Yii::t('app', 'Mobile can not be blank'); ?></div>
                
                <label for=""><?php echo Yii::t('app', 'Email'); ?></label>
                <input type="text" value=""  name="Member[member_email]" />
                <div hidden="" style="color:#a94442" id="error_member_email"><?php echo Yii::t('app', 'Email can not be blank'); ?></div>
                
                <label for=""><?php echo Yii::t('app', 'Note'); ?></label>
                <textarea name="Member[member_note]" rows="10" cols="30" ></textarea>
                
                <label for=""><?php echo Yii::t('app', 'Position'); ?></label>
                <?php echo $ListSetup->getSelectOptionList("position",false,"Member[position]"); ?>
                    
                <label for=""><?php echo Yii::t('app', 'Profile picture'); ?></label>
                <input type="file" name="avatar" />
                <div hidden="" style="color:#a94442" id="error_member_picture"><?php echo Yii::t('app', 'Profile Picture can not be blank'); ?></div>
                    
                <label for=""><?php echo Yii::t('app', 'Trainer code'); ?></label>
                <input  value="<?php echo $nextManage->getDisplayTrainerCode();?>" disabled="" readonly="" name="Member[trainer_code]"  >
            </fieldset>

        </div>
    </div>
    <div class="parkclub-newm-right-footer">
        <button type="submit" id="btn-submit-trainer"><?php echo Yii::t('app', 'SAVE'); ?></button>
    </div>
</div>   
    

<?php ActiveForm::end(); ?>
<script>
    $( document ).ready(function() {
        var trainer_id = '<?php echo $model->member_id; ?>';
        var tour_step = '<?php echo Yii::$app->session['tour-step']; ?>';
        if(tour_step=='<?php echo app\models\Config::TOUR_TRAINER; ?>')
        {
            tour_trainer.restart();
            tour_trainer.start();
            tour_trainer.goTo(2);
        }
    });
    function change_resident(resident)
    {
//        alert(resident);
        if(resident == 1)
        {
            $('.member-unit').show();
        }
        else
        {
            $('.member-unit').hide();
            $('#unit').val("");
        }
    }
    
    function popScanWebcome(member_id){
        $('#html_webcome').remove();
        $('.take_picture').hide();
        $('.view_picture').show(); 
        $('.modal-content').css({'min-height':'400px'});
        $('#modal-content-welcome').load('<?php echo Yii::$app->urlManager->createUrl('/members/default/webcomescan'); ?>',{member_id:member_id},
            function(data){
                $('#view_picture_after').show();
                $('#take_picture').show();
                $('#view_picture').hide(); 
            });
    }
    
    function check_data(){
        var first_name = $('#member_first_name').val();
        var member_surname = $('#member_surname').val();
        var member_address = $('#member_address').val();
        var member_mobile = $('#member_mobile').val();
        var already_mobile = $('#already_mobile').val();
        var mobile = $('#member_mobile').val();
        var check = true;
        if(first_name.trim()==""){
            check = false;
            $('#error_member_first_name').show();
        }
        else{
            $('#error_member_first_name').hide();
        }
        if(member_surname.trim()==""){
            check = false;
            $('#error_member_surname').show();
        }
        else{
            $('#error_member_surname').hide();
        }
        if(member_address.trim()==""){
            check = false;
            $('#error_member_address').show();
        }
        else{
            $('#error_member_address').hide();
        }
        
        if(member_mobile.trim()==""){
            check = false;
            $('#error_member_mobile').show(); 
        }else{
            $('#error_member_mobile').hide(); 
        }
        
        if(already_mobile==1)
            check = false;

        return check;
    }
    function checkMember(value,feild)
    {
            if(value=="")
            {
                $('#already_mobile').val(1);
                $('#error_member_mobile').show();
                $('#error_member_mobile').text("Mobile can not be blank.");
            }
            else if(isNaN(value) == true)
            {
                $('#already_mobile').val(1);
                $('#error_member_mobile').show();
                $('#error_member_mobile').text("Mobile must be number.");
            }
            else
            {
                $('#error_member_mobile').hide();
                $.ajax({
                        url: "<?php echo Yii::$app->urlManager->createUrl('/members/default/checkmember');?>",
                        data: {value:value,feild:feild},
                        success: function (data) {
                            if (data ==1) {
                                $('#error_member_mobile').show();
                                $('#error_member_mobile').text("Mobile has already been taken.");
                                $('#already_mobile').val(1);
                            }
                            else
                            {
                                $('#error_member_mobile').text("");
                                $('#already_mobile').val(0);
                            }
                        }
                });
            }
    }
    function checkID(value,feild)
    {
            if(value=="" || value=="undefined")
            {
                $('#already_id').val(1);
                $('#error_id_card').show();
                $('#error_id_card').text("ID card can not be blank.");
            }
            else
            {
                $('#error_id_card').hide();
                $.ajax({
                        url: "<?php echo Yii::$app->urlManager->createUrl('/members/default/checkmember');?>",
                        data: {value:value,feild:feild},
                        success: function (data) {
                            if (data ==1) {
                                $('#error_id_card').show();
                                $('#error_id_card').text("ID card has already been taken.");
                                $('#already_id').val(1);
                            }
                            else
                            {
                                $('#error_id_card').text("");
                                $('#already_id').val(0);
                            }
                        }
                });
            }
        
    }
</script>