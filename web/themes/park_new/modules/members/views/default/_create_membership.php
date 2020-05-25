<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use kartik\password\PasswordInput;
use app\models\ListSetup;
use app\modules\members\models\Membership;
use app\modules\membership_type\models\MembershipType;
use app\models\NextIds;
use app\models\Config;
/* @var $this yii\web\View */
/* @var $model app\modules\members\models\Members */
/* @var $form yii\widgets\ActiveForm */
$ListSetup = new ListSetup();
$MembershipType= new MembershipType();
$arr_membershipType= $MembershipType->getDropDown(false,$model->member_id);
$MembershipType= new Membership();
$modelMemberShip= new Membership();
$nextManage = new NextIds();
$next_card_no = $nextManage->getDisplayCardNo();
$next_agreementno = $nextManage->getDisplayAgreementNo();
$disabled="disabled";
$membership_id=(isset($_GET['membership_id']) && $_GET['membership_id']>0)?$_GET['membership_id']:"";
if($membership_id>0)
{

    $model->membership_type_id=$modelMembership->membership_type_id;
    if($modelMembership->membership_startdate !="0000-00-00")
        $modelMemberShip->membership_startdate=$modelMembership->membership_startdate;
    if($modelMembership->membership_enddate !="0000-00-00")
        $modelMemberShip->membership_enddate=$modelMembership->membership_enddate;
    $modelMemberShip->membership_code=$next_card_no=$modelMembership->membership_code;
    $modelMemberShip->membership_status=$modelMembership->membership_status;
    if($modelMemberShip->membership_status == Membership::STATUS_ACTIVE_MEMBERSHIP)
        $disabled="";
    $next_agreementno=$modelMembership->membership_barcode;
    $modelMemberShip->membership_limit_number_of_session=$modelMembership->membership_limit_number_of_session;
}

$config = new Config();
$config = $config::find()->one();

?>

<?php $form = ActiveForm::begin(['options' => ['onSubmit'=>"return check_info(); "]]);  ?>
        <div id="members-index" class="parkclub-wrapper parkclub-wrapper-search ">
            <div class="parkclub-rectangle parkclub-shadow">
               <div class="parkclub-invoice">
                    <div class="parkclub-newm-left-title">
                        <div class="parkclub-header-left">
                            <a href="<?php echo YII::$app->urlManager->createUrl(['members/default/update?id='.$_GET['id']]); ?>"><i class="glyphicon glyphicon-circle-arrow-left"></i></a>
                            <?php echo Yii::t('app','Add memberships'); ?>
                        </div>
                    </div>
                   <div class="parkclub-newm ">
                       <fieldset>
                           <label for=""><b><?php echo Yii::t('app','Member'); ?>:</b></label>
                           <b><?php echo $model->getMemberFullName($model->member_id); ?></b>
                           <br><br>
                           <label for=""><?php echo Yii::t('app','Type'); ?>:</label>
                           <?= $form->field($model, 'membership_type_id')->dropDownList($arr_membershipType,['onChange'=>"check_membership_code(1)" ])->label(false) ?>
                           <div hidden="" class="error-form" style="color:#a94442" id="error_membership_type"><?php echo Yii::t('app','Membership Type can not be blank'); ?></div>
                           
                           <label for=""><?php echo Yii::t('app','Status'); ?>:</label>
                            <?php
                            if(!isset($modelMemberShip->membership_status))
                                $modelMemberShip->membership_status = 'Inactive';
                            echo $form->field($modelMemberShip, 'membership_status')->dropDownList($modelMemberShip->getArrayStatus(),['onChange'=>"check_membership_code(2)" ])->label(false) ?>
                            <div hidden="" class="error-form" style="color:#a94442" id="error_membership_type"><?php echo Yii::t('app','Membership Type can not be blank'); ?></div>
                           
                           <label for=""><?php echo Yii::t('app','Card No'); ?>:</label>
                            <?= $form->field($modelMemberShip, 'membership_code')->textInput(['value'=>$next_card_no,'onkeyup'=>'check_membership_code()'])->label(false) ?>
                           <div hidden="" class="error-form" style="color:#a94442" id="error_membership_code"></div>
                           
                           <label for=""><?php echo Yii::t('app','Agreement No'); ?>:</label>
                           <?= $form->field($modelMemberShip, 'membership_barcode')->textInput(['value'=>$next_agreementno,'onkeyup'=>'check_membership_code()','readonly'=>true])->label(false) ?>
                           <div hidden="" class="error-form" style="color:#a94442" id="error_membership_code"></div>
                           
                           <label for=""><?php echo Yii::t('app','From'); ?>:</label>
                            <?php 
                            $value_start_date = date('d/m/Y');
                            if($modelMemberShip->membership_startdate && $modelMemberShip->membership_startdate!="0000-00-00")
                                $value_start_date=  date ('d/m/Y', strtotime($modelMemberShip->membership_startdate));
                            echo $form->field($modelMemberShip, 'membership_startdate')->widget(DatePicker::classname(), [
                                'options' => [
                                    'placeholder' =>Yii::t('app','Enter start date'),
                                    'onChange'=>'check_membership_code()',
                                     $disabled=>'true',
                                    'value' => $value_start_date,
                                    ],
                                'pluginOptions' => [
                                    'autoclose'=>true,
                                    'format' => 'dd/mm/yyyy',
                                    // 'startDate'=> date('d/m/Y')
                                ],
                                'type' => DatePicker::TYPE_COMPONENT_APPEND,
                            ])->label(false);
                            ?>
                           <div hidden="" class="error-form" style="color:#a94442" id="error_membership_startdate"><?php echo Yii::t('app','Membership Startdate can not be blank'); ?></div>
                           
                           
                           <label for=""><?php echo Yii::t('app','To'); ?>:</label>
                            <?php 
                            $value_end_date = date('d/m/Y');
                            if($modelMemberShip->membership_enddate && $modelMemberShip->membership_enddate!="0000-00-00")
                                $value_end_date=  date ('d/m/Y', strtotime($modelMemberShip->membership_enddate));
                            echo $form->field($modelMemberShip, 'membership_enddate')->widget(DatePicker::classname(), [
                                'options' => ['placeholder' => Yii::t('app','Enter end date'),'onChange'=>'check_membership_code()',$disabled=>'true','value'=>$value_end_date],
                                'pluginOptions' => [
                                    'autoclose'=>true,
                                    'format' => 'dd/mm/yyyy',
                                    // 'startDate'=> date('d/m/Y'),
                                    
                                ],
                                
                                'type' => DatePicker::TYPE_COMPONENT_APPEND,
                            ])->label(false);
                            ?>
                           <div hidden="" class="error-form" style="color:#a94442" id="error_membership_enddate"><?php echo Yii::t('app','Membership Enddate can not be blank'); ?></div>
                           <?php //print_r($modelMemberShip); ?>
                           <label for=""><?php echo Yii::t('app', 'Limit the number of sessions'); ?></label>
                            <?= $form->field($modelMemberShip, 'membership_limit_number_of_session')->textInput(['maxlength' => true])->label(false); ?>
                        </fieldset>
                    </div>
                   <div class="parkclub-footer" style="text-align: center">
                       <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-success']) ?>
                   </div>
               </div>
            </div>
        </div>
<input type="text" id="check_membershipcode" hidden="" value="0" />
<?php ActiveForm::end(); ?>

<script>
    $(document).ready(function(){
		<?php if($modelMembership->isNewRecord) {?>
        loadEnddate();
		<?php } ?>
    });
    function check_info()
    {
        
        var check=1;
        
//        var membership_code = $('#membership-membership_code').val();
        
        
        var check = $('#check_membershipcode').val();
        if(check == 0) {
			$.blockUI();
            return true;
        } else
            return false;
    }
    
    function check_membership_code(param)
    {
        var membership_type_id = $("#members-membership_type_id").val();
        var membership_startdate = $("#membership-membership_startdate").val();
        var membership_enddate = $("#membership-membership_enddate").val();
        var membership_code = $("#membership-membership_code").val();
        var membership_status=$('#membership-membership_status').val();
        var membership_id='<?php echo $membership_id;?>';
        $('#check_membershipcode').val(0);
       
        membership_startdate = membership_startdate.split("/").reverse().join("-");
        membership_enddate = membership_enddate.split("/").reverse().join("-");
        var d1 = new Date(membership_startdate);
        var d2 = new Date(membership_enddate);
//        alert(d1);
//        alert(d2);
        if(membership_status == "Active")
        {
            $('#membership-membership_startdate').prop("disabled", false);
            $('#membership-membership_enddate').prop("disabled", false);
        }
        else
        {
            $('#membership-membership_startdate').attr("disabled", true);
            $('#membership-membership_enddate').attr("disabled", true);
        }
        if(membership_code == "")
        {
            $('#error_membership_code').show();
            $('#check_membershipcode').val(1);
        }
        else
            $('#error_membership_code').hide();
        $.ajax({
                url: "<?php echo Yii::$app->urlManager->createUrl('/members/default/checkmembershipcode');?>",
                data: {value:membership_code,feild:"membership_code",membership_id:membership_id},
                success: function (data) {
                    if (data ==1) {
                        $('#check_membershipcode').val(1);
                        $('#error_membership_code').show();
                        $('#error_membership_code').text("Membership Code has already been taken.");
                    } 
                    else
                    {
                        $('#error_membership_code').hide();
//                        $('#check_membershipcode').val(0);
                    }
                }
        });
        
        if(membership_type_id == 0)
        {
            $('#error_membership_type').show();
            $('#check_membershipcode').val(1);
        }
        else
        {
            $('#error_membership_type').hide();
        }
        if(membership_startdate == "" && membership_status == "Active")
        {
            $('#error_membership_startdate').show();
            $('#check_membershipcode').val(1);
        }
        else
        {
            $('#error_membership_startdate').hide();
        }
        
        if(membership_enddate == "" && membership_status == "Active")
        {
            $('#error_membership_enddate').show();
            $('#check_membershipcode').val(1);
        }
        else
        {
            $('#error_membership_enddate').hide();
        }
        if(d1 > d2)
        {
             $('#error_membership_enddate').text("<?php echo Yii::t('app','End date must be greater than start date');?>");
             $('#error_membership_enddate').show();
             $('#check_membershipcode').val(1);
        }
		if(param==1)
			loadEnddate();
		else {
			if(param!=2) {
				<?php if(!$config->customisable_membership_date) { ?>	
				loadEnddate();
				<?php } ?>
				}
			}
		}	
    
    function loadEnddate(){
        var membership = $("#members-membership_type_id").val();
        var start_date = $('#membership-membership_startdate').val();
        $.ajax({
            'type':'POST',
            'url':'<?php echo Yii::$app->urlManager->createUrl('/members/default/membership-end-date') ?>',
            'data':{membership_id:membership,start_date:start_date},
            success:function(data){
                data = jQuery.parseJSON(data);
                $('#membership-membership_enddate').val(data.enddata);
                $('#membership-membership_limit_number_of_session').val(data.limit);
				var membership_startdate = $("#membership-membership_startdate").val();
				var membership_enddate = $("#membership-membership_enddate").val();
				membership_startdate = membership_startdate.split("/").reverse().join("-");
				membership_enddate = membership_enddate.split("/").reverse().join("-");
				var d1 = new Date(membership_startdate);
				var d2 = new Date(membership_enddate);
				if(d1 > d2)
				{
					 $('#error_membership_enddate').text("<?php echo Yii::t('app','End date must be greater than start date');?>");
					 $('#error_membership_enddate').show();
					 $('#check_membershipcode').val(1);
				} else {
					$('#error_membership_enddate').hide();
					 $('#check_membershipcode').val(0);
				}
			}
        });
    }
</script>