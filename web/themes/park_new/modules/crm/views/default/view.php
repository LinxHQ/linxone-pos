<?php
use yii\helpers\Html;
use kartik\select2\Select2;
use app\models\ListSetup;
use app\models\User;
$list_setup = new ListSetup();
$user = new User();
$crm_user_arr_select = array();
foreach ($crm_user_arr as $key => $value) {
    $crm_user_arr_select[] = $key;
}
//Call permisstion
$BasicPermission = new app\modules\permission\models\BasicPermission();
$m = 'members';
$canAdd = $BasicPermission->checkModules($m, 'add');
$dropdow_required_action = $list_setup->getSelectOptionList('required_action');
$dropdow_crm_note_status = $list_setup->getSelectOptionList('crm_note_status');
$required_action = ListSetup::getItemByList('required_action');
$crm_note_status = ListSetup::getItemByList('crm_note_status');
?>
<div class="parkclub-subtop-bar">
    <div class="parkclub-nameplate"><div class="parkclub-iconbg"><img src="<?php echo Yii::$app->urlManager->baseUrl; ?>/image/park_new/members.png" alt=""></div> <h3><?php echo Yii::t('app', 'CRM GUEST');?></h3></div>
    <div class="parkclub-search">
        
    </div>
</div>
<form method="POST" name="frm_crm_guest" action="">
    <div class="parkclub-wrapper parkclub-wrapper-search">
        <div class="parkclub-rectangle parkclub-shadow">
            <div style="text-align: center;">
                <div class="parkclub-newm-left-title">
                    <div class="parkclub-header-left">
                        <a href="<?php echo YII::$app->urlManager->createUrl(['crm/default/index']); ?>"><i class="glyphicon glyphicon-circle-arrow-left"></i></a>
                        <?php echo $model->getMemberFullName($model->member_id); ?>
                    </div>
                </div>
                <img style="margin: 10px 0 10px 0;" width="180px" src="<?php echo $model->getMemberImages($model->member_id);?>">
            </div>
            <table class="table parkclub-table" style="text-align: left;">
                <tr>
                    <td style="width: 20%;"><?php echo Yii::t('app', 'Person in charge');?></td>
                    <td>
                        <?php
                                echo Select2::widget([
                                    'name' => "person_in_charge",
                                    'data' =>$user_array,
                                    'value' => $crm_user_arr_select,
                                    'options' => [
                                        'placeholder' => 'Choose district',
                                        'width'=>'600px',
                                        'id'=>'district',
                                        'multiple' => true,
                                    ],
                                ]);
                        ?>
                    </td>
                </tr>
                <tr>
                    <td><?php echo Yii::t('app', 'Within');?>:</td>
                    <td><?php echo $list_setup->getSelectOptionList('crm_within',false,'Member[member_crm_within]',$event="style='width:30%;float:left;margin-left:0;'",$model->member_crm_within); ?></textarea>
                </tr>
                <tr>
                    <td><?php echo Yii::t('app', 'Description');?>:</td>
                    <td><textarea  rows="4" name="Member[member_crm_description]" class="form-control" style="margin-left:0"><?php echo $model->member_crm_description; ?></textarea>
                </tr>
                <tr>
                    <td><?php echo Yii::t('app', 'Status');?></td>
                    <td>
                        <?php
                        echo $list_setup->getSelectOptionList('crm_status',false,'crm_status',$event="style='width:30%;float:left;margin-left:0;'",$model->member_crm_status);
                        ?>
                        <?php if($model->member_crm_status==1){ ?>
                        &nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo YII::$app->urlManager->createUrl('members/default/addmembership?id='.$model->member_id) ?>"><button type="button" class="btn btn-success" style="#fff"> <?php echo Yii::t('app', 'Add MemberShip');?></button></a>
                        <?php } ?>
                    </td>
                </tr>
                <tr>
                    <td><?php echo Yii::t('app', 'Level');?></td>
                    <td>
                        <?php
                            echo $list_setup->getSelectOptionList('crm_level',false,'member_crm_level',$event="style='width:30%;float:left;margin-left:0;'",$model->member_crm_level);
                        ?>
                    </td>
                </tr>
            </table>
                <div class="parkclub-footer" style="text-align: center">
                <button class="btn btn-success" type="submit"><?php echo Yii::t('app', 'Save');?></button>
            </div>
        </div>
    </div>

    <div class="parkclub-wrapper parkclub-wrapper-search">
        <div class="parkclub-rectangle parkclub-shadow">
            <div class="parkclub-newm-left-title">
                <div class="parkclub-header-left">
                    <?php echo Yii::t('app', 'Action');?>
                </div>
                <div class="parkclub-header-right">
                    <button class="btn btn-info" onclick="formSendEmail(); return false;"> <?php echo Yii::t('app', 'Send email');?></button>
                </div>
            </div>
            <div class="parkclub-rectangle-content">
                <table id="crm_note" > 
                    <tbody>
                        <tr >
                                <th>#</th>
                                <th style="width: 22%;"><?php echo Yii::t('app', 'Required action');?></th>
                                <th style="width: 15%;"><?php echo Yii::t('app', 'Status');?></th>
                                <th style="width: 28%;"><?php echo Yii::t('app', 'Note');?></th>
                                <th style="width: 15%;"><?php echo Yii::t('app', 'Person in change');?></th>
                                <th style="width: 12%;"><?php echo Yii::t('app', 'Date');?></th>
                                <th></th>
                        </tr>
                        <?php $i=1; foreach ($crm_note_guest as $item) { ?>
                            <tr style="line-height: 2.0;">
                                <td ><?php echo $i; ?></td>
                                <td><span class="edit-hide-<?php echo $item->member_note_id; ?>" id="view_crm_action_<?php echo $item->member_note_id; ?>"><?php echo (isset($required_action[$item->required_action])) ? $required_action[$item->required_action] : ""; ?></span>
                                    <span class="disable edit-show-<?php echo $item->member_note_id; ?>">
                                        <?php echo $list_setup->getSelectOptionList('required_action',false,'required_action_view',false,$item->required_action,'crm_action_'.$item->member_note_id); ?>
                                    </span>
                                </td>
                                <td><span class="edit-hide-<?php echo $item->member_note_id; ?>" id="view_crm_status_<?php echo $item->member_note_id; ?>"><?php echo (isset($crm_note_status[$item->status])) ? $crm_note_status[$item->status] : " "; ?></span>
                                    <span class="disable edit-show-<?php echo $item->member_note_id; ?>">
                                        <?php echo $list_setup->getSelectOptionList('crm_note_status',false,'crm_note_status_view',false,$item->status,'crm_status_'.$item->member_note_id); ?>
                                    </span>
                                </td>
                                <td>
                                    <span id="view_crm_note_<?php echo $item->member_note_id; ?>" class="edit-hide-<?php echo $item->member_note_id; ?>"><?php echo $item->note; ?></span>
                                    <textarea class="disable edit-show-<?php echo $item->member_note_id; ?>" id="crm_note_<?php echo $item->member_note_id; ?>" name="crm_note_<?php echo $item->member_note_id; ?>"><?php echo $item->note; ?></textarea>
                                </td>
                                <td><?php echo $user->getFullName($item->created_by); ?></td>
                                <td><?php echo $list_setup->getDisplayDate($item->note_date); ?></td>
                                <td >
                                    <a class="btn btn-success btn-small disable edit-show-<?php echo $item->member_note_id; ?>" href="#" onclick="saveEditLine(<?php echo $item->member_note_id; ?>);return false;"><?php echo Yii::t('app','Save'); ?></a>
                                    <a class="btn btn-warning btn-small disable edit-show-<?php echo $item->member_note_id; ?>" href="#" onclick="cancelEditLine(<?php echo $item->member_note_id; ?>);return false;"><?php echo Yii::t('app','Cancel'); ?></a>
                                    <a class="edit-hide-<?php echo $item->member_note_id; ?>" href="#" onclick="editLine(<?php echo $item->member_note_id; ?>);return false;"><i class="glyphicon glyphicon-edit"></i></a>
                                    <a class="edit-hide-<?php echo $item->member_note_id; ?>" href="<?php echo YII::$app->urlManager->createUrl('/crm/default/delete?id='.$item->member_note_id); ?>" onclick="return confirm('Are you sure you want to delete this item?');"><i class="glyphicon glyphicon-trash"></i></a>
                                </td>
                            </tr>                      
                        <?php $i++; } ?>
                    </tbody>
                </table>
            </div>
            <div class="parkclub-footer">
                <button class="btn btn-primary" onclick="addNote(); return false;"> <?php echo Yii::t('app', 'Add Note');?></button>
                <button class="btn btn-success" type="submit"><?php echo Yii::t('app', 'Save');?></button>
            </div>
        </div>
    </div>
    
    <div class="parkclub-wrapper parkclub-wrapper-search">
        <div class="parkclub-rectangle parkclub-shadow">
            <div class="parkclub-newm-left-title">
                <div class="parkclub-header-left">
                    <?php echo Yii::t('app', 'Guest Information');?>
                </div>
            </div>
            <div class="parkclub-rectangle-content">
                <table  > 
                    <tr>
                        <td><?php echo Yii::t('app', 'Full Address');?></td>
                        <td><?php echo $model->getMemberFullAddress($model->member_id); ?></td>
                    </tr>
                    <tr>
                        <td><?php echo Yii::t('app', 'Email');?></td>
                        <td><?php echo $model->member_email; ?></td>

                    </tr>
                    <tr>
                        <td><?php echo Yii::t('app', 'Mobile');?></td>
                        <td><?php echo $model->member_mobile; ?></td>
                    </tr>
                    <tr>
                        <td><?php echo Yii::t('app', 'Phone');?></td>
                        <td><?php echo $model->member_phone; ?></td>
                    </tr>
                    <tr>
                        <td colspan="2"><a href="<?php echo YII::$app->urlManager->createUrl('members/default/update?id='.$model->member_id) ?>"><?php echo Yii::t('app', 'View details');?></a></td>
                    </tr>
                </table>
            </div>

        </div>
    </div>
</form>

<!-- MODAL SEND EMAIL -->
    <div id="bs-model-send-email" data-backdrop="static" data-keyboard="false" class="modal fade bs-example-modal-lg popupCheckin" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
        <div class="modal-dialog modal-lg" role="document" style="width: 500px; background: #fff">
            <div  class="modal-send-email">
                <div class="modal-header" style="background-color: rgb(50, 205, 139); color:#fff; text-align:center">
                    <button class="close" type="button" data-dismiss="modal" aria-hidden="true">×</button>
               <div class="fa fa-2x fa-calendar-plus-o" style="float:left;margin-right:10px"></div>
               <div>
                   <h4 class="modal-title"><?php echo YII::t('app', "Send email") ?></h4>
               </div>
               </div>
                <div class="modal-body" id="modal-conten-send-email" style="text-align: left; padding: 20px;">
                    <form id="form-send-email">
                        <input type="hidden" class="form-control" name="member_id" id="member_id" value="<?php echo $model->member_id;  ?>">
                        <div class="form-group">
                          <label for="email_to"><?php echo Yii::t('app', 'To'); ?> *</label>
                          <input type="email" class="form-control" name="email_to" id="email_to" value="<?php echo $model->member_email;  ?>">
                        </div>
                        <div class="form-group">
                          <label for="email_cc"><?php echo Yii::t('app', 'Cc'); ?></label>
                          <input type="email" class="form-control" name="email_cc" id="email_cc" placeholder="<?php echo Yii::t('app','example1@gmail.com, example2@gmail.com, ...');?>">
                        </div>
                        <div class="form-group">
                          <label for="email_subject"><?php echo Yii::t('app', 'Subject'); ?> *</label>
                          <input type="text" class="form-control" name="email_subject" id="email_subject" placeholder="<?php echo Yii::t('app','Enter subject');?>">
                        </div>
                        <div class="form-group">
                          <label for="email_massages"><?php echo Yii::t('app', 'Message'); ?> *</label>
                          <textarea class="form-control" rows="5" name="email_massage" id="email_massage" placeholder="<?php echo Yii::t('app','Enter massage');?>"></textarea>
                        </div>
                        <button type="button" onclick="sendEmail();return false;" class="btn btn-success"><?php echo Yii::t('app','Send');?></button>
                   </form> 
                </div>
            </div>
        </div>
    </div>
    <input id = "isset" type="hidden" <?php if(isset(Yii::$app->user->identity->menu)){ ?>value =<?php echo Yii::$app->user->identity->menu;} ?>>
</div>
<!-- END SEND EMAIL -->

<script type="text/javascript">
    var i=1;
    function addNote(){
        var tr = '<tr id="crm_note_tr_'+i+'"><td><a href="#" onclick="deleteNote('+i+'); return false;"><i class="glyphicon glyphicon-trash"></i></a></td>';
            tr +="<td><?php echo $dropdow_required_action;?></td>";
            tr +="<td><?php echo $dropdow_crm_note_status;?></td>";
            tr +='<td><textarea class="form-control" style="width:100%" name="crm_note[]" rows="2" cols="30"></textarea></td>';
            tr +='<td>&nbsp;</td>';
            tr +='<td></td><td></td></tr>';
        $('#crm_note tbody').append(tr);
        i = i + 1;
    }
    
    function editLine(id){
        $('.edit-show-'+id).removeClass('disable');
        $('.edit-hide-'+id).addClass('disable');
        return false;
    }
    
    function cancelEditLine(id){
        $('.edit-show-'+id).addClass('disable');
        $('.edit-hide-'+id).removeClass('disable');
        return false;
    }
    
    function saveEditLine(id){
        var note = $('#crm_note_'+id).val();
        var action = $('#crm_action_'+id).val();
        var status = $('#crm_status_'+id).val();
        $.ajax({
            'url':'<?php echo Yii::$app->urlManager->createUrl('/crm/default/update-line'); ?>',
            'type':'POST',
            'data':{id:id,note:note,action:action,status:status},
            success:function(data){
                data = jQuery.parseJSON(data);
                if(data.status=="success"){
                    $('#crm_note_'+id).val(data.note);
                    $('#view_crm_note_'+id).html(data.note);
                    $('#view_crm_action_'+id).html(data.action);
                    $('#view_crm_status_'+id).html(data.crm_status);
                    cancelEditLine(id);
                }
                else{
                    alert("<?php echo Yii::t('app', 'Update error.'); ?>");
                }
            }
        });
        return false;
    }
    
    function deleteNote(id){
        $('#crm_note_tr_'+id).remove();
    }
    function validateEmail(email) {
        var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(email);
    }
    
    function sendEmail(){
        var data_form = $('#form-send-email').serialize();
        var check_validate = 0;
        if($('#email_to').val()==""){
            $('#email_to').addClass("error");
            check_validate = 1;
        }
        else if(validateEmail($('#email_to').val())==false){
            $('#email_to').addClass("error");
            check_validate = 1;
        }else{
            $('#email_to').removeClass("error");
        }
        if($('#email_cc').val()!="" && validateEmail($('#email_cc').val())==false){
            $('#email_cc').addClass("error");
            check_validate = 1;
        }else{
            $('#email_cc').removeClass("error");
        }
        if($('#email_subject').val()==""){
            $('#email_subject').addClass("error");
            check_validate = 1;
        }else{
            $('#support_subject').removeClass("error");
        }
        if($('#email_massage').val()==""){
            $('#email_massage').addClass("error");
            check_validate = 1;
        }else{
            $('#email_massage').removeClass("error");
        }
        if(check_validate==0){
            $('#bs-model-send-email').modal('hide');
            $.blockUI();
            $.ajax({
                type:'POST',
                url:'<?php echo Yii::$app->urlManager->createUrl('/crm/default/send-email'); ?>',
                data:data_form,
                success:function(data){
                    data = JSON.parse(data);
                    if(data.status=='success'){
                        swal({
                          title: '<?php echo Yii::t("app","Successfully sent!"); ?>',
                          text: '<?php echo Yii::t("app","Thanks for contact us. We will revert to you as soon as possible."); ?>',
                          type: "success",
                          showConfirmButton: true,
                          confirmButtonText:'<?php echo Yii::t('app','Close')?>',
                        });
                    }
                    else
                        alert('Fail');
                    $.unblockUI();
                }
            });
        }
    }
    
    function formSendEmail(){
        $('#help-tour').click();
        $('#bs-model-send-email').modal('show');
    }
</script>

