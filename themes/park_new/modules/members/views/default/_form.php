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
//$next_id++;
$id=0;
// $card_no = $nextManage->getNextId('next_card_id');
$card_no = $modelMemberShip->getNextNumberMembershipCode();
if(isset($_GET['id']))
{
    $id=$_GET['id'];
    $modelMemberShip = Membership::findAll(['member_id' => $_GET['id']]);
    
}
$form_element=0;
$lable_family=Yii::t('app', "Master Account");
?>

<div class="parkclub-new-member-wap">
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data','onSubmit'=>"return test(); ",'name'=>'w0[]']],['enableAjaxValidation' => true]);  ?>
        <div class="test01">
        <?php echo $this->render("_form_member",['id'=>$id]) ;?>
        </div>
        <div class="parkclub-newm-right-footer">
            <button id='create-member' type="submit"><?php echo Yii::t('app','SAVE');?></button>
            <?php if($id==0) { ?>
                <button type="button" class="parkclub-subaccountbtn" onclick="add_new_account();"><?php echo Yii::t('app','NEW SUBACCOUNT');?></button>
            <?php } ?>
        </div>
    <?php ActiveForm::end(); ?>
</div>
<script>
    $( document ).ready(function() {
        var intall_data = '<?php echo Yii::$app->session['install-data']; ?>';
        var istour = '<?php echo Yii::$app->session['tour']; ?>';
        if(intall_data==2 && istour==1){
            $('input[name="Member[0][first_name]"]').val('<?php echo Yii::t('app','Perter'); ?>');
            $('input[name="Member[0][surname]"]').val('<?php echo Yii::t('app','David'); ?>');
            $('#member_birthday0').val('04/03/1998');
            $('input[name="Member[0][member_address]"]').val('<?php echo Yii::t('app','Trần thái tông - Cầu giấu - Hà nội'); ?>');
            $('#district').val(3);
            $('#city').val(24);
            $('input[name="Member[0][member_mobile]').val('0965051543');
            $('input[name="Member[0][member_phone]').val('0421245534');
            $('input[name="Member[0][member_email]').val('perter12@gmail.com');
            $('input[name="Member[0][id_card]').val('186973943');
            $('input[name="Member[0][username]').val('0965051543');
            
            tour_no_demo.restart();
            tour_no_demo.start();
            tour_no_demo.goTo(8);
        }
        var tour_step = '<?php echo Yii::$app->session['tour-step']; ?>';
        if(tour_step=='<?php echo app\models\Config::TOUR_MEMBER; ?>')
        {
            $('input[name="Member[0][first_name]"]').val('<?php echo Yii::t('app','Perter'); ?>');
            $('input[name="Member[0][surname]"]').val('<?php echo Yii::t('app','David'); ?>');
            $('#member_birthday0').val('04/03/1998');
            $('input[name="Member[0][member_address]"]').val('<?php echo Yii::t('app','Trần thái tông - Cầu giấu - Hà nội'); ?>');
            $('#district').val(3);
            $('#city').val(24);
            $('input[name="Member[0][member_mobile]').val('096576645');
            $('input[name="Member[0][member_phone]').val('04212457543');
            $('input[name="Member[0][member_email]').val('perter12@gmail.com');
            $('input[name="Member[0][id_card]').val('26523412');
            $('input[name="Member[0][username]').val('096576645');
            tour_member.restart();
            tour_member.start();
            tour_member.goTo(2);
        }
    });
    var form_element_nex=1;
    var form_element_arr = new Array("0");
    var card_no = "<?php echo $card_no;?>";
    function add_new_account()
    {
        var html;
        card_no++;
        var membership_select=$("select[name='Member[0][membership_type_id]']").val();
        $.ajax({
                url: "<?php echo Yii::$app->urlManager->createUrl('/members/default/create');?>",
                data: {form_element:form_element_nex,membership_select:membership_select,card_no:card_no},
                type:"POST",
                success: function (data) {
                    if (data) {
                        $(".test01").append(data);
                        change_membership_type(form_element_nex);
                    } 
                }
        });
        
        $(html).appendTo('.test01');
        form_element_arr.push(form_element_nex);
        form_element_nex++;


    }
    function copy_info(feild,id)
    {
        var member_address=$("input[name='Member[0][member_"+feild+"]']").val();
       
        $("input[name='Member["+id+"][member_"+feild+"]']").val(member_address);
        return false;
        
    }
    
    function checkMember(value,feild,id)
    {
            if(value=="")
            {
                $('#tesst_'+id).val(1);
                $('#error_member_mobile_'+id).show();
                $('#error_member_mobile_'+id).text("<?php echo Yii::t('app','Mobile can not be blank.');?>");
            }
            else if(isNaN(value) == true)
            {
                $('#tesst_'+id).val(1);
                $('#error_member_mobile_'+id).show();
                $('#error_member_mobile_'+id).text("<?php echo Yii::t('app','Mobile must be number.');?>");
            }
            else
            {
                $('#error_member_mobile_'+id).hide();
                $.ajax({
                        url: "<?php echo Yii::$app->urlManager->createUrl('/members/default/checkmember');?>",
                        data: {value:value,feild:feild},
                        success: function (data) {
                            if (data ==1) {
                                $('#error_member_mobile_'+id).show();
                                $('#error_member_mobile_'+id).text("<?php echo Yii::t('app','Mobile has already been taken.');?>");
                                $('#tesst_mobile_'+id).val(1);
                            }
                            else
                            {
                                $('#error_member_mobile_'+id).text("");
                                $('#tesst_mobile_'+id).val(0);
                                $('input[name="Member['+id+'][username]').val(value);
                            }
                        }
                });
            }
            
            $('#err_mobilr').val(0);
            var check_mobile;
            $('input[type="text"][class="member_mobile[]"]').each(function(){
               
                check_mobile = $(this).val();
                if(check_mobile=="" || isNaN(check_mobile))
                {
                    $('#err_mobilr').val(1);
                }
                else
                {
                    $.ajax({
                        url: "<?php echo Yii::$app->urlManager->createUrl('/members/default/checkmember');?>",
                        data: {value:check_mobile,feild:feild},
                        success: function (data) {
                            if (data ==1) {
                               $('#err_mobilr').val(1);
                            }
                            
                        }
                    });
                }
        
            });
            
            
        
    }
    function checkbarcode(value,feild,id)
    {
        var membership_type_id = $('#membership_type_id').val();
        
        if(membership_type_id == 0)
            return false;
        else
        {
            if(value=="")
            {

                $('#error_member_barcode_'+id).show();
                $('#error_member_barcode_'+id).text("Member barcode can not be blank.");

            }
            else
            {
                $('#error_member_barcode_'+id).hide();
                $.ajax({
                        url: "<?php echo Yii::$app->urlManager->createUrl('/members/default/checkmember');?>",
                        data: {value:value,feild:feild},
                        success: function (data) {
                            if (data ==1) {
                                $('#error_member_barcode_'+id).show();
                                $('#error_member_barcode_'+id).text("Member barcode has already been taken.");

                            }
                            else
                            {
                                $('#error_id_card_'+id).text("");
                            }
                        }
                });
            }

            $('#check_err_member_barcode').val(0);
            var check_barcode;

            $(".member_barcode").each(function() {

                check_barcode = $(this).val();

                if(check_barcode=="")
                {
                    $('#check_err_member_barcode').val(1);
                }
                else
                {
                    $.ajax({
                        url: "<?php echo Yii::$app->urlManager->createUrl('/members/default/checkmember');?>",
                        data: {value:check_barcode,feild:feild},
                        success: function (data) {
                            if (data ==1) {
                               $('#check_err_member_barcode').val(1);
                            }

                        }
                    });
                }

            });
        }
    }
    function checkID(value,feild,id)
    {
        
            if(value=="")
            {
                $('#tesst_'+id).val(1);
                $('#error_id_card_'+id).show();
                $('#error_id_card_'+id).text("<?php echo Yii::t('app','ID card can not be blank.') ?>");
            }
            else
            {
                $('#error_id_card_'+id).hide();
                $.ajax({
                        url: "<?php echo Yii::$app->urlManager->createUrl('/members/default/checkmember');?>",
                        data: {value:value,feild:feild},
                        success: function (data) {
                            if (data ==1) {
                                $('#error_id_card_'+id).show();
                                $('#error_id_card_'+id).text("<?php echo Yii::t('app','ID card has already been taken.') ?>");
                                $('#tesst_Passport_'+id).val(1);
                            }
                            else
                            {
                                $('#error_id_card_'+id).text("");
                                $('#tesst_Passport_'+id).val(0);
                            }
                        }
                });
            }
            
            $('#check_err_id').val(0);
            var check_mobile;
            $('input[type="text"][class="id_card[]"]').each(function(){
               
                check_mobile = $(this).val();
                if(value=="" || isNaN(value))
                {
                    $('#check_err_id').val(1);
                }
                else
                {
                    $.ajax({
                        url: "<?php echo Yii::$app->urlManager->createUrl('/members/default/checkmember');?>",
                        data: {value:check_mobile,feild:feild},
                        success: function (data) {
                            if (data ==1) {
                                $('#check_err_id').val(1);
                            }
                            
                        }
                    });
                }
        
            });
        
    }
    
    function test()
    {
        var membership_type_id = $('#membership-membership_type_id').val();
        var membership_startdate = $('#membership-membership_startdate').val();
        var membership_enddate = $('#membership-membership_enddate').val();
        var member_picture = $('#members-member_picture').val();
        var member_mobile = $('#members-member_mobile').val();
        var member_phone = $('#members-member_phone').val();
        var membership_code=$('#membership-membership_code').val();
        
        $('#error_membership_type').hide();
        $('#error_membership_startdate').hide();
        $('#error_membership_enddate').hide();
        $('#error_member_picture').hide();
        $('#error_membership_code').hide();
        
        var check = 1;
        var member_fullname="";
        var member_address="";
        var member_mobile="";
        var company_name="";
        var company_tax_code="";
        var company_address="";
        var member_phone="";
        var membership_dob="";
        var id_card="";
        var barcode="";
        var city="";
        var first_name = "";
        var surname = "";
        
        //check fullname , address , DOB ,mobile, phone
        for(var i = 0; i< form_element_arr.length; i++)
        {

            first_name = $("input[name='Member["+form_element_arr[i]+"][first_name]']").val();
            surname = $("input[name='Member["+form_element_arr[i]+"][surname]").val();
            member_fullname=$("input[name='Member["+form_element_arr[i]+"][member_name]']").val();
            member_address=$("input[name='Member["+form_element_arr[i]+"][member_address]']").val();
            member_mobile=$("input[name='Member["+form_element_arr[i]+"][member_mobile]']").val();
            company_name=$("input[name='Member["+form_element_arr[i]+"][company_name]']").val();
            company_tax_code=$("input[name='Member["+form_element_arr[i]+"][company_tax_code]']").val();
            company_address=$("input[name='Member["+form_element_arr[i]+"][company_address]']").val();
            id_card=$("input[name='Member["+form_element_arr[i]+"][id_card]']").val();
            barcode=$("input[name='Member["+form_element_arr[i]+"][member_barcode]']").val();
            member_phone=$("input[name='Member["+form_element_arr[i]+"][member_phone]']").val();
            membership_type_id=$("select[name='Member["+form_element_arr[i]+"][membership_type_id]']").val();
            membership_code=$("input[name='Member["+form_element_arr[i]+"][membership_code]']").val();
            membership_startdate=$("input[name='Member["+form_element_arr[i]+"][membership_startdate]']").val();
            membership_enddate=$("input[name='Member["+form_element_arr[i]+"][membership_enddate]']").val();
            membership_enddate=$("input[name='Member["+form_element_arr[i]+"][membership_enddate]']").val();
            member_picture=$("input[name='avatar"+form_element_arr[i]+"']").val();
            city = $("select[name='Member["+form_element_arr[i]+"][city]']").val();

            if(first_name == ""){
                $("#error_first_name_"+form_element_arr[i]).show();
                check=0;
            }
            else
            {
                $("#error_first_name_"+form_element_arr[i]).hide();
            }
            
            if(surname == ""){
                $("#error_surname_"+form_element_arr[i]).show();
                check=0;
            }
            else
            {
                $("#error_surname_"+form_element_arr[i]).hide();
            }
        
            if(city == ""){
                $("#error_company_city"+form_element_arr[i]).show();
                check=0;
            }
            else
            {
                $("#error_company_city"+form_element_arr[i]).hide();
            }
            
            if(member_fullname == "")
            {
                $("#error_member_name_"+form_element_arr[i]).show();
                check=0;
            }
            else
                $("#error_member_name_"+form_element_arr[i]).hide();
            if(member_address == "")
            {
                $("#error_member_address_"+form_element_arr[i]).show();
                check=0;
            }
            else
                $("#error_member_address_"+form_element_arr[i]).hide();
            if(company_name != "" || company_tax_code != "" || company_address != "")
            {
                if(company_name == "")
                {
                    $("#error_company_name"+form_element_arr[i]).show();
                    check=0;
                }
                else 
                {
                    $("#error_company_name"+form_element_arr[i]).hide();
                }
                if(company_tax_code == "")
                {
                    $("#error_tax_code"+form_element_arr[i]).show();
                    check=0;
                }
                else 
                {
                    $("#error_tax_code"+form_element_arr[i]).hide();
                }
                if(company_address == "")
                {
                    $("#error_company_address"+form_element_arr[i]).show();
                    check=0;
                }
                else 
                {
                    $("#error_company_address"+form_element_arr[i]).hide();
                }
            }
            else
            {
                $("#error_company_name"+form_element_arr[i]).hide();
                $("#error_tax_code"+form_element_arr[i]).hide();
                $("#error_company_address"+form_element_arr[i]).hide();
            }
            
            if(member_mobile == "")
            {
                $("#error_member_mobile_"+form_element_arr[i]).show();
                check=0;
            }
            else if(member_mobile.length<=8){
                $('#error_member_mobile_'+form_element_arr[i]).text('<?php echo Yii::t('app',"Phone must contain at least 8 characters.");?>');
                $('#error_member_mobile_'+form_element_arr[i]).show();
                check=0;  
            }
            else if($('#tesst_mobile_'+form_element_arr[i]).val()==1)
            {
                check=0;
                $("#error_member_mobile_"+form_element_arr[i]).show();
            }
            
            if(id_card == "")
            {
                $("#error_id_card_"+form_element_arr[i]).show();
                check=0;
            }
            else if($('#tesst_Passport_'+form_element_arr[i]).val()==1)
            {
                check=0;
                $("#error_id_card_"+form_element_arr[i]).show();
            }
            if(isNaN(member_phone) == true)
            {
                $('#error_member_phone_'+form_element_arr[i]).text('<?php echo Yii::t('app',"Phone must be number");?>');
                $('#error_member_phone_'+form_element_arr[i]).show();
                check=0;
            }

            else
            {
                $('#error_member_phone_'+form_element_arr[i]).hide();
            }
            if(barcode == "" && membership_type_id > 0)
            {
                $("#error_member_barcode_"+form_element_arr[i]).show();
                check=0;
            }

            if(membership_type_id > 0)
            {

                if(membership_code == "")
                {
                    $('#error_membership_code_'+form_element_arr[i]).show();
                    check=0;
                }
//                else
//                {
//                    $.ajax({
//                            url: "<?php echo Yii::$app->urlManager->createUrl('/members/default/checkmembershipcode');?>",
//                            data: {value:membership_code,feild:"membership_code"},
//                            success: function (data) {
//                                if (data ==1) {
//                                    check = 0;
//                                    $('#error_membership_code_'+form_element_arr[i]).show();
//                                    $('#error_membership_code_'+form_element_arr[i]).text("Membership Code has already been taken.");
//                                } 
//                            }
//                    });
//                }
                if(membership_type_id == 0)
                {
                    $('#error_membership_type_'+form_element_arr[i]).show();
                    check=0;
                }
            }
        }
        
        
        var w0 =$("input[name='avatar"+form_element_arr[i]+"]']").val();
        $('<input />').attr('type', 'hidden')
                .attr('name', "w01")
                .attr('value', w0)
                .appendTo('form');
        
            
        var check_mobile = $('#err_mobilr').val();
        var check_err_id = $('#check_err_id').val();
        var check_err_member_barcode = $('#check_err_member_barcode').val();
        
        if(check_mobile == 1 || check == 0 || check_err_id == 1 || check_err_member_barcode == 1)
            return false;
        else {
			$.blockUI();
            return true;
		}	
    }
    
    change_membership_type();
    function change_membership_type(form_element)
    {
        var membership = $("#membership_type_id").val();
		if(membership != 0){
			$(".card_no_dispaly").show();
			$(".guest_code").hide();
			loadEnddate();
			
		} else {
			$(".card_no_dispaly").hide();
			$(".guest_code").show();
		}
		
		var membership_select=$("select[name='Member[0][membership_type_id]']").val();
        var membership_startdate=$("#membership_from0").val();
        var membership_enddate=$("#membership_to0").val();
        
        var membership_type_id;
        
		var i = 1;
		if(!isNaN(form_element))
			i = form_element;
        
		for( i ; i< form_element_arr.length; i++)
        {
            $("select[name='Member["+form_element_arr[i]+"][membership_type_id]']").val(membership_select);
            $("#membership_from"+form_element_arr[i]).val(membership_startdate);
            $("#membership_to"+form_element_arr[i]).val(membership_enddate);
        }
        
    }
    
    function delete_member(id)
    {
        $('.test'+id).remove();
        var item_id = form_element_arr.indexOf(id);
        form_element_arr.splice(item_id,1);
    }
    
    function loadEnddate(){
        var membership = $("#membership_type_id").val();
        var start_date = $('#membership_from0').val();
        $.ajax({
            'type':'POST',
            'url':'<?php echo Yii::$app->urlManager->createUrl('/members/default/membership-end-date') ?>',
            'data':{membership_id:membership,start_date:start_date},
            success:function(data){
                data = jQuery.parseJSON(data);
                $('#membership_to0').val(data.enddata);
                $('#number_of_session_0').val(data.limit);
				var membership_enddate=$("#membership_to0").val();
				
				for(var i = 1; i< form_element_arr.length; i++)
				{
					$("#membership_to"+form_element_arr[i]).val(membership_enddate);
                    $('#number_of_session_'+form_element_arr[i]).val(data.limit);
				}
	            }
	        });
    }
    
</script>


