<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;

//Check permission 
$m = 'facility';
$BasicPermission = new \app\modules\permission\models\BasicPermission();
$canList = $BasicPermission->checkModules($m, 'list');
$canAdd = $BasicPermission->checkModules($m, 'add');
$canEdit = $BasicPermission->checkModules($m, 'update');
$canDelete = $BasicPermission->checkModules($m, 'delete');
$canView = $BasicPermission->checkModules($m, 'view');

if(!$canView){
    echo "You don't have permission with this action.";
    return false;
}
//End check permission


/* @var $this yii\web\View */
/* @var $model app\models\Facility */

$this->title = $model->facility_name;
$ListSetup = new app\models\ListSetup();
$MembershipType = new app\modules\membership_type\models\MembershipType();

$membershipTypeDropdow = $MembershipType->getDropDown();
$dropdow='<select class="form-control select-width-90" name="membership_type_id" id="membership_type_id">';
foreach ($membershipTypeDropdow as $key=>$value)
{
    $dropdow.='<option value='.$key.'>'.$value.'</option>';
}
$dropdow.='</select>';
?>
<?php $form = ActiveForm::begin(); ?>
<div id="members-index" class="parkclub-wrapper parkclub-wrapper-search ">
    <div class="parkclub-rectangle parkclub-shadow">
       <div class="parkclub-invoice">
            <div class="parkclub-newm-left-title">
                <div class="parkclub-header-left">
                    <a href="<?php echo YII::$app->urlManager->createUrl(['facility/default/index']); ?>"><i class="glyphicon glyphicon-circle-arrow-left"></i></a>
                    <?php echo Yii::t('app', 'View Facility'); ?>
                </div>
            </div>
                   <div class="parkclub-newm ">
                        <fieldset>
                            <label for=""><?php echo Yii::t('app', 'First Name'); ?></label>
                            <?= $form->field($model, 'facility_name')->textInput(['maxlength' => true])->label(false); ?>
                            
                            <label for=""><?php echo Yii::t('app', 'Description'); ?></label>
                            <?= $form->field($model, 'facility_description')->textarea(['rows' => 6])->label(false); ?>
                            
                            <div class="parkclub-radio">
                            <div class="parkclub-switch">
                                <input value="0" name="Facility[facility_free]" type="radio" <?php echo (($model->facility_free) ? '' : 'checked'); ?> id="radio1">
                                <label for="radio1"><?php echo Yii::t('app', 'CHARGE'); ?></label>
                                <input value="1" name="Facility[facility_free]" type="radio" <?php echo (($model->facility_free) ? 'checked' : ''); ?> id="radio2">
                                <label for="radio2"><?php echo Yii::t('app', 'FREE'); ?></label>
                            </div>
                            </div>
                        </fieldset>
                   </div>
            <div class="parkclub-footer" style="text-align: center">
                <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-success']) ?>
            </div>
       </div>
    </div>
</div>
<div id="members-index" class="parkclub-wrapper parkclub-wrapper-search ">
    <div class="parkclub-rectangle parkclub-shadow">
       <div class="parkclub-invoice">
            <div class="parkclub-newm-left-title">
                <div class="parkclub-header-left">
                    <?php echo Yii::t('app','Limit');?>
                </div>
            </div>
           <div class="parkclub-rectangle-content">
               <table style="margin-bottom: 0px;" id="table_facility">
                    <tr>
                        <th>#</td>
                        <th width="40%"><?php echo Yii::t('app','Membership type'); ?></th>
                        <th><?php echo Yii::t('app','Limit of Days'); ?></th>
                        <th><?php echo Yii::t('app','Limit of Weeks'); ?></th>
                        <th><?php echo Yii::t('app','Limit of Months'); ?></th>
                        <th width="12%"><?php echo Yii::t('app','Actions'); ?></th>
                    </tr>
                    <?php 
                    $stt_limit = 0;
                    foreach ($facility_book as $facility_book_item) { 
                    $stt_limit++;    
                    ?>
                    <tr id='limit_<?php echo $stt_limit; ?>'>
                        <td><?php echo $stt_limit; ?></td>
                        <td><?php echo (($facility_book_item->membershipType) ? $facility_book_item->membershipType->membership_name : ""); ?></td>
                        <td><?php echo $facility_book_item->faclity_limit_days; ?></td>
                        <td><?php echo $facility_book_item->faclity_limit_week; ?></td>
                        <td><?php echo $facility_book_item->facility_limit_month; ?></td>
                        <td>
                            <a href="<?php echo yii::$app->urlManager->createUrl('/facility/default/update_limit?limit_id='.$facility_book_item->facility_book_id) ?>" ><span class="glyphicon glyphicon-pencil"></span></a>&nbsp;
                            <a onclick="return confirm('<?php echo Yii::t('app','Are you sure you want to delete this item?');?>');"href="<?php echo yii::$app->urlManager->createUrl('/facility/default/delete_limit?facility_id='.$model->facility_id.'&limit_id='.$facility_book_item->facility_book_id) ?>" ><span class="glyphicon glyphicon-remove"></span></a>&nbsp;
                        </td>
                    </tr>
                    <?php } ?>
                </table>
           </div>
       </div>
        <div class="parkclub-footer">
            <button onclick="addLimit(); return false;" id="bnt-add-limit" type="button" class="btn btn-primary" ><?php echo Yii::t('app','Add Limit');?></button>
        </div>
    </div>
</div>
<div id="facility-price">
    
</div>
<?php ActiveForm::end(); ?>
<script type="text/javascript">
    $( document ).ready(function() {
        var tour_step = '<?php echo Yii::$app->session['tour-step']; ?>';
        if(tour_step=='<?php echo app\models\Config::TOUR_FACILITY; ?>')
        {
            tour_facility.restart();
            tour_facility.start();
            tour_facility.goTo(4);
            //$('#bs-model-checkin-endtour').modal('show');
        }
        loadPrice();
    });

    var check=1;
    var count_limit = '<?php echo $stt_limit; ?>';
    var count_price = 100;
    function addLimit()
    {
        count_limit ++;
        var html="";
        html="<tr id='limit_"+count_limit+"'>";
        html+='<td >'+count_limit+'</td>';
        html+='<td><?php echo $dropdow; ?><span class="error" id="limit_error_'+count_limit+'"></span></td>';
        html+="<td><input type='text' value='0' id='faclity_limit_days' name ='faclity_limit_days' ></td>";
        html+="<td><input type='text' value='0' id ='faclity_limit_week' name ='faclity_limit_week' ></td>";
        html+="<td><input type='text' value='0' id ='facility_limit_month' name ='facility_limit_month' ></td>";
       
        html+='<td>\n\
                    <button type="button" id="bnt-save-limit" onclick="saveLimit('+count_limit+');return false;" class="btn btn-success btn-small">Save</button> &nbsp;\n\
                    <span style="cursor: pointer; font-size:18px; color:#a94442" onclick="removeLimit('+count_limit+');" class="glyphicon glyphicon-trash"></span>\n\
                </td>';
        html+="</tr>";

        $('#table_facility').append(html);
    }
    
//    function editPrice(id)
//    {
//        var html="";
//        html="<tr id='limit_"+id+"'>";
//        html+='<td>'+id+'</td>';
//        html+='<td><?php //echo $dropdow; ?><span class="error" id="limit_error_'+id+'"></span></td>';
//        html+="<td><input type='text' onkeyup=check_limit_day() value='0' id='faclity_limit_days' name ='faclity_limit_days' ></td>";
//        html+="<td><input type='text' onkeyup=check_limit_week() value='0' id ='faclity_limit_week' name ='faclity_limit_week' ></td>";
//        html+="<td><input type='text' onkeyup=check_limit_month() value='0' id ='facility_limit_month' name ='facility_limit_month' ></td>";
//       
//        html+='<td>\n\
//                    <button type="button" onclick="savePrice('+id+');return false;" class="btn btn-success btn-small">Save</button> &nbsp;\n\
//                    <span style="cursor: pointer; font-size:18px; color:#a94442" onclick="removePrice('+id+');" class="glyphicon glyphicon-trash"></span>\n\
//                </td>';
//        html+="</tr>";
//
//        $('#limit_'+id).html('');
//        $('#limit_'+id).html(html);
//    }
    
    function saveLimit(id){
        var facility_id = '<?php echo $model->facility_id; ?>';
        var membership_type_id = $('#limit_'+id+' #membership_type_id').val();
        var faclity_limit_days = $('#limit_'+id+' #faclity_limit_days').val();
        var faclity_limit_week = $('#limit_'+id+' #faclity_limit_week').val();
        var facility_limit_month = $('#limit_'+id+' #facility_limit_month').val();
        $.ajax({
            'type':'POST',
            'url':'<?php echo YII::$app->urlManager->createUrl('/facility/default/create-limit-line');  ?>',
            'data':{faclity_limit_days:faclity_limit_days,faclity_limit_week:faclity_limit_week,
                facility_limit_month:facility_limit_month,membership_type_id:membership_type_id,facility_id},
            'success':function(data){
                var responseJSON = jQuery.parseJSON(data);
                if(responseJSON.status=='success')
                    successLimit(id,responseJSON.id);
                else
                    $('#limit_error_'+id).html(responseJSON.message);
            }
        });
    }
    
    function successLimit(id,facility_book_id){
        var membership_type_id = $('#limit_'+id+' #membership_type_id option:selected').text();
        var faclity_limit_days = $('#limit_'+id+' #faclity_limit_days').val();
        var faclity_limit_week = $('#limit_'+id+' #faclity_limit_week').val();
        var facility_limit_month = $('#limit_'+id+' #facility_limit_month').val();
        var html = '<td>'+id+'</td>';
        html += '<td>'+membership_type_id+'</td>';
        html += '<td>'+faclity_limit_days+'</td>';
        html += '<td>'+faclity_limit_week+'</td>';
        html += '<td>'+facility_limit_month+'</td>';
        html += '<td><a href="<?php echo yii::$app->urlManager->createUrl('/facility/default/update_limit?limit_id=') ?>'+facility_book_id+'"><span class="glyphicon glyphicon-pencil"></span></a>&nbsp;';
        html += '<a onclick="return confirm(\'Are you sure you want to delete this item?\');" href="<?php echo yii::$app->urlManager->createUrl('/facility/default/delete_limit?facility_id='.$model->facility_id.'&limit_id=')?>'+facility_book_id+'" ><span class="glyphicon glyphicon-remove"></span></a>&nbsp;'
        html += '</td>';
        $('#limit_'+id).html('');
        $('#limit_'+id).html(html);
    }
    
    function removeLimit(id){
        $('#limit_'+id).remove();
    }
    
    function addPrice()
    {
        count_price ++;
        $.ajax({
            'type':'POST',
            'url':'<?php echo YII::$app->urlManager->createUrl('/facility/default/form-inline-add-price');  ?>',
            'data':{count:count_price},
            'success':function(data){
                $('#table_price').append(data);
                var tour_step = '<?php echo Yii::$app->session['tour-step']; ?>';
                if(tour_step=='<?php echo app\models\Config::TOUR_FACILITY; ?>')
                {
                    tour_facility.restart();
                    tour_facility.start();
                    tour_facility.goTo(8);
                    //$('#bs-model-checkin-endtour').modal('show');
                }
            }
        });
    }
    
    function removePrice(id){
        $('#price_'+id).remove();
    }
    
    function savePrice(id){
        var facility_id = '<?php echo $model->facility_id; ?>';
        var membership_type_id = $('#price_'+id+' #membership_type_id').val();
        var price_name = $('#price_'+id+' #facility_price_name').val();
        var price_tax = $('#price_'+id+' #price_tax').val();
        var facility_price = $('#price_'+id+' #facility_price').val();
        var facility_price_after = $('#price_'+id+' #facility_price_after').val();
        var facility_startdate = $('#facility_startdate_'+id).val();
        var faclity_enddate = $('#faclity_enddate_'+id).val();
        $.ajax({
            'type':'POST',
            'url':'<?php echo YII::$app->urlManager->createUrl('/facility/default/create-price-line');  ?>',
            beforeSend:function(){
                $.blockUI();
            },
            'data':{price_name:price_name,price_tax:price_tax,facility_startdate:facility_startdate,faclity_enddate:faclity_enddate,
                facility_price:facility_price,facility_price_after:facility_price_after,membership_type_id:membership_type_id,facility_id:facility_id},
            'success':function(data){
                var responseJSON = jQuery.parseJSON(data);
                if(responseJSON.status=='success'){
                    loadPrice();
                    // $('#bs-model-checkin-endtour').modal('show');
                }
                else
                    $('#limit_error_'+id).html(responseJSON.message);
                $.unblockUI();
            }
        });
    }
    
    function successPrice(id,facility_price_id){
        var facility_id = '<?php echo $model->facility_id; ?>';
        var membership_type_id = $('#price_'+id+' #membership_type_id option:selected').text();
        var price_name = $('#price_'+id+' #facility_price_name').val();
        var price_tax = $('#price_'+id+' #price_tax').val();
        var facility_price = $('#price_'+id+' #facility_price').val();
        var facility_price_after = $('#price_'+id+' #facility_price_after').val();
        var facility_startdate = $('#facility_startdate_'+id).val();
        var faclity_enddate = $('#faclity_enddate_'+id).val();
        
        var html = '<tr><td width="1%">'+id+'</td>';
        html += '<td width="20%">'+membership_type_id+'</td>';
        html += '<td width="10%">'+price_name+'</td>';
        html += '<td width="10%">'+price_tax+'</td>';
        html += '<td width="10%">'+facility_price+'</td>';
        html += '<td width="10%">'+facility_price_after+'</td>';
        html += '<td width="15%">'+facility_startdate+'</td>';
        html += '<td width="15%">'+faclity_enddate+'</td>';
        html += '<td ><a href="<?php echo yii::$app->urlManager->createUrl('/facility/default/update_price?price_id=') ?>'+facility_price_id+'"><span class="glyphicon glyphicon-pencil"></span></a>&nbsp;';
        html += '</td></tr>';
        $('#price_'+id).html('');
        $('#price_'+id).html(html);
    }
    
    
    function calculatePrice(id,price_after){
        if(price_after==''){
            $('#price_'+id+' #facility_price').val(0);
            return false;
        }
        var tax = $('#price_'+id+' #price_tax option:selected').text();
        var price =(100*parseFloat(price_after))/(100+parseFloat(tax));
        $('#price_'+id+' #facility_price').val(price.toFixed(0));
    }
    
    function calculatePriceAfter(id,price){
        if(price==''){
            $('#price_'+id+' #facility_price_after').val(0);
            return false;
        }
        var tax = $('#price_'+id+' #price_tax option:selected').text();
        var price_after = parseFloat(price) + (parseFloat(price)*parseFloat(tax))/100;
        $('#price_'+id+' #facility_price_after').val(price_after);
    }
    
    function updatePrice(id){
        var tax = $('#price_'+id+' #price_tax option:selected').text();
        var price = $('#price_'+id+' #facility_price').val();
        var price_after = 0;
        if(price!="")
            var price_after = parseFloat(price) + (parseFloat(price)*parseFloat(tax))/100;
        $('#price_'+id+' #facility_price_after').val(price_after);
    }
    
    function loadPrice(){
        var facility_id = '<?php echo $model->facility_id; ?>';
        $('#facility-price').load('<?php echo Yii::$app->urlManager->createUrl('/facility/default/load-price'); ?>',{facility_id:facility_id},function(){
        })
    }
</script>