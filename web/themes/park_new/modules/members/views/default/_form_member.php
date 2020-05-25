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
$Membership= new Membership();
$modelMemberShip= new Membership();
$nextManage = new NextIds();

$next_non_dup_ms = $Membership->getNextNumberMembershipCode();
$card_no = $nextManage->getDisplayCardNo($next_non_dup_ms);
$nextManage->getDisplayMemberBarcode();
if(isset($_POST['card_no']))
    $card_no = $nextManage->getDisplayCardNo($_POST['card_no']);


if(isset($_GET['id']))
{
    $modelMemberShip = Membership::findAll(['member_id' => $_GET['id']]);
   //info master account
}

$model = new app\modules\members\models\Members();
$form_element=0;
$lable_family = Yii::t('app', "Master Account");

$copy_add="";
$copy_email="";

$membership_select="";
if(isset($_POST['membership_select']))
    $membership_select = $_POST['membership_select'];
$icon_delete="";
$hidden ="";


$value_start_date="";$AgreementNo_parent = "";$selected_membership = false;
if(isset($id) && $id>0)
{
    $lable_family = Yii::t('app', "Subaccount");
    $master_info = $Membership->getMemberShipActiveByMember($id);
    if($master_info){
        $membership_select = $master_info->membership_type_id;
        $AgreementNo_parent = $master_info->membership_barcode;
        $selected_membership = true;
    }
    
}
if(isset($_REQUEST['form_element']))
{
    echo '<div class="test'.$_REQUEST['form_element'].'">';
    $form_element = $_REQUEST['form_element'];
    $lable_family = "Subaccount ";
    $copy_add="<input type='button' value='".Yii::t('app', 'Copy address')."' onClick =copy_info('address',".$form_element.");return false;>";
    $copy_email="<input type='button'  href='#' type='text' value='".Yii::t('app', 'Copy email')."' onClick =copy_info('email',".$form_element.");return false;>";
    $icon_delete ='<span style="float:right;cursor:pointer" onClick="delete_member('.$form_element.');"><i class="glyphicon glyphicon-remove red"></i></span>';
    $hidden="hidden";
}
else
{
?>
    <div class="test<?php echo $form_element; ?>" id="form-member-<?php echo $form_element; ?>">
<?php } ?>
<?php
$next_number_barcode = $nextManage->getNextId('next_barcode_id')+$form_element;
$next_number_guestcode= $nextManage->getNextId('next_guest_code')+$form_element;
$next_number_agreement_no = $nextManage->getNextId('next_agreement_id')+$form_element;
$next_barcode = $nextManage->getDisplayMemberBarcode($next_number_barcode);
$next_guestcode = $nextManage->getDisplayGuest($next_number_guestcode);
$agreement_no = $nextManage->getDisplayAgreementNo();

?>
    <input hidden="" value="<?php echo $form_element; ?>" name="account_type[]"/>
    <div class="parkclub-new-member" id="form_membership">
        <div class="parkclub-newm-left parkclub-shadow" style="width: 95%;">
            <div class="parkclub-newm-left-title">
                <a href="<?php echo YII::$app->urlManager->createUrl(['members/default/index']); ?>"><i class="glyphicon glyphicon-circle-arrow-left"></i></a>
                <?php echo $lable_family; ?>
                <?php echo $icon_delete; ?>
            </div>
            
            <!-- INFO BASIC -->
            <div class="col-md-12 new-block-title"><a href="#" onclick="js: $('#info-basic-<?php echo $form_element; ?>').slideToggle(); return false;" >
                    <i class="glyphicon glyphicon-info-sign"></i> <?php echo Yii::t('app', 'Basic information'); ?></a>
            </div>
            <div id="info-basic-<?php echo $form_element; ?>" class="col-md-12 new-block-content">
                <fieldset>
                    <div class="col-md-12">
                    <!-- MODAL WELCOME -->
                        <div class="take_picture" id="take_picture<?php echo $form_element;?>" style="display: none;padding: 20px; ">
                                 <div id="modal-content-welcome<?php echo $form_element;?>">
                                 </div>
                         </div>
                         <div class="view_picture" id="view_picture<?php echo $form_element;?>" style="display: block;">
                             <div class="view_picture_after" id="view_picture_after<?php echo $form_element;?>" style="display: block;">
                                 <img id="member_img<?php echo $form_element;?>" style="margin-top: 0px" src="<?php echo Yii::$app->urlManager->baseUrl; ?>/image/park_new/unknown.png" />
                                 </div>
                             <textarea id="member_picture<?php echo $form_element;?>" style="display: none;" name="Member[<?php echo $form_element;?>][member_picture_webcome]"></textarea>

                             <button onclick="popScanWebcome(0,<?php echo $form_element;?>);return false;"><?php echo Yii::t('app', 'Take a picture / Upload file photo');?></button>
                        </div>
                     <!-- END MODAL WELCOME -->
                    </div>
                    <div class="col-md-12">
                        <div class="col-md-6">
                            <div class="col-md-6">
                                <label for="" class="required"><?php echo Yii::t('app', 'Surname');?></label>
                                <input type="text" value="" name="Member[<?php echo $form_element;?>][surname]" />
                                <div style="color:#a94442" class="parkclub-newm-error" id="error_surname_<?php echo $form_element;?>" hidden=""><?php echo Yii::t('app', 'Surname can not be blank');?></div>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="" class="required"><?php echo Yii::t('app', 'First Name');?></label>
                                <input type="text" value="" name="Member[<?php echo $form_element;?>][first_name]" />
                                <div style="color:#a94442" class="parkclub-newm-error" id="error_first_name_<?php echo $form_element;?>" hidden=""><?php echo Yii::t('app', 'First name can not be blank');?></div> 
                            </div>

                        </div>
                        <div class="col-md-6">
                            <label for="" class="required"><?php echo Yii::t('app', 'Mobile');?></label>
                            <input onchange="checkMember(this.value,'member_mobile',<?php echo $form_element;?>)" onkeyup="checkMember(this.value,'member_mobile',<?php echo $form_element;?>)" type="text" value="" class="member_mobile[]" name="Member[<?php echo $form_element;?>][member_mobile]"  />
                            <div hidden="" style="color:#a94442" class="parkclub-newm-error" id="error_member_mobile_<?php echo $form_element;?>"><?php echo Yii::t('app', 'Mobile can not be blank');?></div>
                            <input type="hidden" value="0" id="tesst_mobile_<?php echo $form_element;?>" />
                            <input type="hidden" value="0" id="tesst_Passport_<?php echo $form_element;?>" />
                            <input type="hidden" value="0" id="err_mobilr" />
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="col-md-6">
                            <label for=""><?php echo Yii::t('app', 'DOB');?></label>
                            <?php
                                echo DatePicker::widget([
                                    'name' => 'Member['.$form_element.'][member_birthday]',
                                     'id' => 'member_birthday'.$form_element,
                                     'value' => '',

                                     'options' => ['placeholder' => Yii::t('app','Choose DOB')],
                                     'pluginOptions' => [
                                         'format' => 'dd/mm/yyyy',
                                         'todayHighlight' => true,
                                         'showMonthAfterYear'=>true
                                     ],
                                    'type' => DatePicker::TYPE_COMPONENT_APPEND,
                                 ]);
                            ?>
                        </div>
                        <div class="col-md-6">
                            <label for=""><?php echo Yii::t('app', 'Email');?></label>
                            <input type="text" value=""  name="Member[<?php echo $form_element;?>][member_email]" /><?php echo $copy_email;?>
                            <div hidden="" style="color:#a94442" id="error_member_email_<?php echo $form_element;?>"><?php echo Yii::t('app', 'Email can not be blank');?></div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="col-md-6">
                            <label for="" class="required"><?php echo Yii::t('app', 'Address');?></label>
                            <input type="text" value="-" name="Member[<?php echo $form_element;?>][member_address]" /><?php echo $copy_add;?>
                            <div hidden="" style="color:#a94442" class="parkclub-newm-error" id="error_member_address_<?php echo $form_element;?>"><?php echo Yii::t('app','Address can not be blank');?></div>
                        </div>
                        <div class="col-md-6">
                            <div class="col-md-6">
                                <label for="" class="required"><?php echo Yii::t('app', 'Province/City');?></label>
                                <?php
                                    $province_arr = ListSetup::getItemByList('city');
                                    asort($province_arr);
                                    $arr_city = array(""=>Yii::t('app', 'Choose Province/City')) + $province_arr;
                                    echo $ListSetup->getSelectOptionList("city",$arr_city,"Member[$form_element][city]","class='city' onchange='loadDistrict(this.value,".$form_element.");return false;'",24,false,'');
                                ?>
                                <div hidden="" class="parkclub-newm-error" style="color:#a94442" id="error_company_city<?php echo $form_element;?>">Province/City can not be blank</div>
                            </div>
                            <div class="col-md-6">
                                <label for=""><?php echo Yii::t('app', 'District');?></label>
                                <?php 
                                    $district_arr = array(0=>Yii::t('app', 'Choose District')) + ListSetup::getItemByList('Hà Nội');
                                    echo $ListSetup->getSelectOptionList("district",$district_arr,"Member[$form_element][district]","class='district'",false,false,'');
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="col-md-6">
                            <label for="" class="required"><?php echo Yii::t('app', 'Identity Card / Passport');?></label>
                            <input onchange="checkID(this.value,'id_card',<?php echo $form_element;?>)" onkeyup="checkID(this.value,'id_card',<?php echo $form_element;?>)" name="Member[<?php echo $form_element;?>][id_card]"  class="id_card[]">
                            <div hidden="" style="color:#a94442" class="parkclub-newm-error" id="error_id_card_<?php echo $form_element;?>"><?php echo Yii::t('app', 'ID card can not be blank');?></div>
                            <input type="text" id="check_err_id" hidden="" value="0" />
                        </div>
                        <div class="col-md-6">
                            <label for=""><?php echo Yii::t('app', 'Note');?></label>
                            <textarea name="Member[<?php echo $form_element;?>][member_note]" rows="10" cols="30" ></textarea>
                        </div>
                    </div>
                </fieldset>
            </div>
            <!-- END INFO BASIC -->
            
            <!-- MEMBERSHIP -->
            <div class="col-md-12 new-block-title">
                <a href="#" onclick="js: $('#info-membership-<?php echo $form_element; ?>').slideToggle(); return false;" >
                    <i class="glyphicon glyphicon-credit-card"></i> <?php echo Yii::t('app', 'Guest / Membership'); ?>
                </a>
            </div>
            <div id="info-membership-<?php echo $form_element; ?>" class="col-md-12 new-block-content">
                <fieldset>
                <div class="col-md-12">
                    <label for=""><?php echo Yii::t('app', 'Membership');?></label>
                    <?php
                    $event = "";
                    if($form_element == 0)
                        $event="onChange=change_membership_type();";
                    else
                        $event="disabled";
                    if($selected_membership)
                        $event="disabled";
                    echo $ListSetup->getSelectOptionList("membership_type_id",$arr_membershipType,"Member[".$form_element."][membership_type_id]",$event,$membership_select,false,false,'');?>
                    <?php if($event=="disabled"){ ?>
                        <input type="hidden" name="Member[<?php echo $form_element; ?>][membership_type_id]" value="<?php echo $membership_select; ?>" />
                    <?php } ?>
                    <div hidden="" style="color:#a94442" class="parkclub-newm-error" id="error_membership_type_<?php echo $form_element;?>">Membership Type can not be blank</div>
                </div>
                <div class="col-md-12">
                    <span class='guest_code'>
                        <label for=""><?php echo Yii::t('app', 'Guest Code');?></label>
                        <input type="hidden" id="guest_code" name="Member[<?php echo $form_element;?>][guest_code_hide]" value="<?php echo $next_guestcode; ?>">
                        <input  id="guest_code_<?php echo $form_element; ?>" value="<?php echo $next_guestcode; ?>"  class="guests_code" name="Member[<?php echo $form_element;?>][guest_code]"  >
                    </span>
                     <div class="col-md-6 card_no_dispaly" style="padding-top: 12px; display: none;">
                        <label for=""><?php echo Yii::t('app', 'Member Barcode');?>:</label>
                        <b><?php echo $next_barcode; ?></b><br>
                    </div>
                    
                     <div class="col-md-6 card_no_dispaly" style="padding-bottom: 12px; padding-top: 12px; display: none;">
                        <label for=""><?php echo Yii::t('app', 'Agreement No');?>:</label>
                        <b><?php echo ($AgreementNo_parent!="") ? $AgreementNo_parent : $agreement_no; ?></b><br>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="col-md-6">
                        <div class="card_no_dispaly" style="padding-top: 12px; display: none;">
                           <label for=""><?php echo Yii::t('app', 'Card No');?>:</label>
                           <input id="card_no_value_<?php echo $form_element;?>" type="text" value="<?php echo $card_no; ?>" onChange= "check_membership_code(<?php echo $form_element;?>)" name="Member[<?php echo $form_element;?>][membership_code]"  /> 
                           <div hidden="" style="color:#a94442" id="error_membership_code_<?php echo $form_element;?>"><?php echo Yii::t('app', 'MemberShip Code can not be blank');?>.</div>
                       </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card_no_dispaly" style="padding-top: 12px; display: none;">
                           <label for=""><?php echo Yii::t('app', 'Status');?>:</label>
                            <?php
                                echo yii\bootstrap\Html::dropDownList('Member['.$form_element.'][membership_status]','',$Membership->getArrayStatus());
                            ?>
                       </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="col-md-6">
                        <div class="card_no_dispaly" style="padding-top: 12px; display: none;">
                           <label for=""><?php echo Yii::t('app', 'From');?>:</label>
                            <?php
                                echo DatePicker::widget([
                                    'name' => 'Member['.$form_element.'][membership_startdate]',
                                     'id' => 'membership_from'.$form_element,
                                     'value' => date('d/m/Y'),
                                    
                                     'options' => ['placeholder' => 'Enter start date'],
                                         // 'onChange'=>'change_membership_type()'],
                                     'pluginOptions' => [
                                         'format' => 'dd/mm/yyyy',
                                         'todayHighlight' => true,
                                         'showMonthAfterYear'=>true,
                                         
                                     ],
                                    'type' => DatePicker::TYPE_COMPONENT_APPEND,
                                 ]);
                            ?>
                       </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card_no_dispaly" style="padding-top: 12px; display: none;">
                           <label for=""><?php echo Yii::t('app', 'To');?>:</label>
                            <?php
                                echo DatePicker::widget([
                                    'name' => 'Member['.$form_element.'][membership_enddate]',
                                     'id' => 'membership_to'.$form_element,
                                     'value' => '',

                                     'options' => ['placeholder' => 'Enter end date'],
                                     'pluginOptions' => [
                                         'format' => 'dd/mm/yyyy',
                                         'todayHighlight' => true,
                                         'showMonthAfterYear'=>true
                                     ],
                                    'type' => DatePicker::TYPE_COMPONENT_APPEND,
                                 ]);
                            ?>
                       </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="col-md-6">
                        <div class="card_no_dispaly" style="padding-top: 12px; display: none;">
                           <label for=""><?php echo Yii::t('app', 'Limit the number of sessions');?>:</label>
                           <input id="number_of_session_<?php echo $form_element;?>" type="text" value="" name="Member[<?php echo $form_element;?>][membership_limit_number_of_session]"  /> 
                       </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card_no_dispaly">
                        <div class="nav-title"><?php echo Yii::t('app', 'Login Account'); ?></div>
                        <label for=""><?php echo Yii::t('app', 'Send email to member');?></label>
                        <input type="checkbox" style="width: auto; height: 10px;margin-left:10px" name="Member[<?php echo $form_element;?>][sendmail]" ><br>
                        <label for="" style="margin-top: 5px;"><?php echo Yii::t('app', 'Username');?></label>
                        <input type="text" name="Member[<?php echo $form_element;?>][username]" >
                        <div hidden="" style="color:#a94442" id="error_tax_code<?php echo $form_element;?>"><?php echo Yii::t('app', 'Username can not be blank');?></div> 
                        <label for=""><?php echo Yii::t('app', 'Password');?></label>
                        <input type="text" name="Member[<?php echo $form_element;?>][password]" value="<?php echo $next_barcode; ?>" >
                        <div hidden="" style="color:#a94442" id="error_tax_code<?php echo $form_element;?>"><?php echo Yii::t('app', 'Password can not be blank');?></div> 
                    </div>
                </div>
            </div>
            <!-- END MEMBERSHIP -->
            
            <!-- INFO OTHER -->
            <div class="col-md-12 new-block-title">
                <a href="#" onclick="js: $('#info-ọther-<?php echo $form_element; ?>').slideToggle(); return false;" >
                    <i class="glyphicon glyphicon-info-sign"></i> <?php echo Yii::t('app', 'Other information'); ?>
                </a>
            </div>
            <div id="info-ọther-<?php echo $form_element; ?>" class="col-md-12 new-block-content" style="display: none;">
                <fieldset>
                <div class="col-md-12">
                    <div class="col-md-6">
                        <label for=""><?php echo Yii::t('app', 'Given Name');?></label>
                        <input type="text" value="" name="Member[<?php echo $form_element;?>][given_name]" />
                    </div>
                    <div class="col-md-6">
                        <label for=""><?php echo Yii::t('app', 'Phone');?></label>
                        <input type="text" value=""  name="Member[<?php echo $form_element;?>][member_phone]"  />
                        <div hidden="" style="color:#a94442" class="parkclub-newm-error" id="error_member_phone_<?php echo $form_element;?>"><?php echo Yii::t('app', 'Mobile can not be blank');?></div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="col-md-6">
                        <label for=""><?php echo Yii::t('app', 'Source');?></label>
                        <?php 
                            $district_arr = array(0=>Yii::t('app', 'Choose Source')) + ListSetup::getItemByList('source');
                            echo $ListSetup->getSelectOptionList("source",$district_arr,"Member[".$form_element."][member_profession]",false,false,false,'');
                        ?>
                    </div>
                    <div class="col-md-6">
                        <label for=""><?php echo Yii::t('app', 'Company Name');?></label>
                        <input type="text" name="Member[<?php echo $form_element;?>][company_name]" >
                        <div hidden="" style="color:#a94442" id="error_company_name<?php echo $form_element;?>"><?php echo Yii::t('app', 'Company Name can not be blank');?></div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="col-md-6">
                        <label for=""><?php echo Yii::t('app', 'Company Adress');?></label>
                        <input type="text" name="Member[<?php echo $form_element;?>][company_address]" >
                        <div hidden="" style="color:#a94442" id="error_company_address<?php echo $form_element;?>"><?php echo Yii::t('app', 'Company Address can not be blank');?></div>
                    </div>
                    <div class="col-md-6">
                        <label for=""><?php echo Yii::t('app', 'Company Tax Code');?></label>
                        <input type="text" name="Member[<?php echo $form_element;?>][company_tax_code]" >
                        <div hidden="" style="color:#a94442" id="error_tax_code<?php echo $form_element;?>"><?php echo Yii::t('app', 'Company Tax Code can not be blank');?></div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="col-md-6">
                        <div class="parkclub-radio">
                            <label class="parkclub-resident" for=""><?php echo Yii::t('app', 'Resident');?></label>
                            <div class="parkclub-switch">
                                <input value="1" name="Member[<?php echo $form_element ?>][resident]" type="radio" id="radio1">
                                <label for="radio1" onclick="change_resident(1,<?php echo $form_element ?>);"><?php echo Yii::t('app', 'YES');?></label>
                                <input value="0" name="Member[<?php echo $form_element ?>][resident]" type="radio" id="radio2" checked>
                                <label for="radio2" onclick="change_resident(0,<?php echo $form_element ?>);"><?php echo Yii::t('app', 'NO');?></label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <span class="member-unit-<?php echo $form_element ?>" style="display: none;">
                            <label><?php echo Yii::t('app', 'Unit');?></label>
                            <textarea id="unit" name="Member[<?php echo $form_element;?>][unit]" style="margin-bottom: 5px;" ></textarea>
                        </span>
                    </div>
                </div>
                    </fieldset>
            </div>
            <!-- END INFO OTHER -->
        </div>
    </div>
</div>
<script>

    function change_resident(resident,element_id)
    {
        var radios = $('input:radio[name="Member['+element_id+'][resident]"]');
        if(resident == 1)
        {
            $('.member-unit-'+element_id).show();
            radios.filter('[value=0]').prop('checked', false);
            radios.filter('[value=1]').prop('checked', true);
        }
        else
        {
            $('.member-unit-'+element_id).hide();
            radios.filter('[value=0]').prop('checked', true);
            radios.filter('[value=1]').prop('checked', false);
            $('#unit').val("");
        }
    }
    
    function popScanWebcome(member_id,element_id){
        $('#html_webcome').remove();
        $('.take_picture').hide();
        $('.view_picture').show(); 
        $('.modal-content'+element_id).css({'min-height':'400px'});
        $('#modal-content-welcome'+element_id).load('<?php echo Yii::$app->urlManager->createUrl('/members/default/webcomescan'); ?>',{member_id:member_id,element_id:element_id},
            function(data){
                $('#view_picture_after'+element_id).show();
                $('#take_picture'+element_id).show();
                $('#view_picture'+element_id).hide(); 
            });
    }
    
    function loadDistrict(city,form_id){
        $.ajax({
            type:'POST',
            url:'<?php echo Yii::$app->urlManager->createUrl("/members/default/load-district")?>',
            data:{city:city},
            success:function(data){
                $('#form-member-'+form_id+' #district').html(data);
            }
        });
    }
    function check_membership_code(param)
    {
      
        var membership_code = $("#card_no_value_"+param).val(); 
        $.ajax({
                url: "<?php echo Yii::$app->urlManager->createUrl('/members/default/checkmembershipcode');?>",
                data: {value:membership_code,feild:"membership_code",membership_id:""},
                success: function (data) {
                    if (data ==1) { 
                        $('#error_membership_code_'+param).show();
                        $('#error_membership_code_'+param).text("Membership Code has already been taken."); 
                    } 
                    else
                    {
                        $('#error_membership_code_'+param).hide(); 
                    }
                }
        }); 
	}	
</script>