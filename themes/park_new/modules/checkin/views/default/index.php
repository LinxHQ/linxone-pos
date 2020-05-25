<?php
use yii\helpers\Html;
use yii\grid\GridView;
use kartik\tabs\TabsX;
use app\modules\checkin\models\MembersCheckin;
$memberCheckin = new MembersCheckin();

//Check permission 
$m = 'checkin';
$BasicPermission = new \app\modules\permission\models\BasicPermission();
$DefinePermission = new \app\modules\permission\models\DefinePermission();

$canCheckin_out = $DefinePermission->checkFunction($m, 'checkin-out');
$canList = $BasicPermission->checkModules($m, 'list');
if(!$canList){
    echo "You don't have permission with this action.";
    return ;
}
//End check permission

$keyserch = (isset($_GET['key'])) ? $_GET['key'] : '';
?>
<input id="enter_success" value="" type="text" style="width: 0px;position: absolute;" />
<div class="parkclub-subtop-bar">
    <div class="parkclub-nameplate"><div class="parkclub-iconbg"><img src="<?php echo Yii::$app->urlManager->baseUrl; ?>/image/park_new/check.png" alt=""></div> <h3>Check in/out</h3></div>
    <div class="parkclub-search">
        <input type="text" placeholder="<?php echo Yii::t('app','By Card Id');?>" id="txt_card_id" value="<?php echo $keyserch; ?>">
        <button class="parkclub-searchbtn" onclick="searchMemberShip();" type="submit"><img src="<?php echo Yii::$app->urlManager->baseUrl; ?>/image/park_new/search.png"></button>
        <button class="parkclub-checkbtn" onclick="popcheckin();" type="submit">CHECK IN/OUT</button>
    </div>
</div>

<!-- MODAL CHECKIN -->
<div id="bs-model-checkin" class="modal fade bs-example-modal-lg popupCheckin" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div id="modal-content-checkin" class="modal-content">
        </div>
    </div>
</div>
<!-- END MODAL CHECKIN -->

<div class="parkclub-wrapper parkclub-wrapper-search" id="view_only_checkin">
    <?php echo $this->render('_view_only_checkin',['dataProvider'=>$dataProvider,'only_chekin'=>0]); ?>
</div>
<script>
    var tour_step = '<?php echo Yii::$app->session['tour-step']; ?>';
    $( document ).ready(function() {
        var intall_data = '<?php echo Yii::$app->session['install-data']; ?>';
        var istour = '<?php echo Yii::$app->session['tour']; ?>';
        if((intall_data==2 || intall_data==1) && istour==1){
            localStorage.clear();
            data_demo.init();
            data_demo.restart();
            data_demo.start();
        }
        if(tour_step=='<?php echo app\models\Config::TOUR_CHECKIN; ?>')
        {
            tour_checkout.restart();
            tour_checkout.start();
        }
        
    });

    $('#txt_card_id').focus();
//    $('#checkin-out').focus();
    $(function(){ $("input[name=q]").focus();
    });
    (function($) {
        

        
    $('#ms-keysearch').keypress(function(event){
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if (keycode == '13') {
          searchMemberShip();
        }
    });
    $('#txt_card_id').keypress(function(event){
        var keycode1 = (event.keyCode ? event.keyCode : event.which);
        if ($('.popupCheckin').css('display') == 'none') {
                if (keycode1 == 13) {
                    popcheckin();
                }
        }
    });


    setInterval(reloadToalCheckInOut,60000);

    })(jQuery);
    
    
    
    
    
    function popcheckin(){
        var card_id=$('#txt_card_id').val();
        if(card_id==""){
            alert("Please, enter card id.");
            return false;
        }
        $('.modal-content').css({'min-height':'200px'});
        $('#modal-content-checkin').load('<?php echo Yii::$app->urlManager->createUrl('/checkin/default/getheckin'); ?>',{"card_id":card_id},
            function(data){
                $('#bs-model-checkin').modal('show'); 
            });
    }
    
    function popcheckin_line(card_id){
        $('.modal-content').css({'min-height':'200px'});
        $('#modal-content-checkin').load('<?php echo Yii::$app->urlManager->createUrl('/checkin/default/getheckin'); ?>',{"card_id":card_id},
            function(data){
                $('#bs-model-checkin').modal('show'); 
                
            });
    }
    function hideModel(id){
        $('#'+id).modal('hide');
    }
    function searchMemberShip(){
        var keysearch = $('#txt_card_id').val();
        if(keysearch==""){
            alert("Please, enter keyword.");
            return false;
        }
        reloadGridview();
    };

    function reloadToalCheckInOut(){
        $.ajax({
            'type':'POST',
            'url':'<?php echo YII::$app->urlManager->createUrl('/checkin/default/reload_toal_check_in_out') ?>',
            'data':{},
            success:function(data){
                data = jQuery.parseJSON(data);
                $('#total_daily_checkin').html(data.checkin);
                $('#total_daily_checkout').html(data.checkout);
            }
        });
    }
    
    function checkin(member_id,membership_type_id,membership_id){
		var member_trainings = $('input[name=pt_checkin]:checked').map(function()
            {
                return $(this).val();
            }).get();		
		$.blockUI();
        $.ajax({
            'type':'POST',
            'url':'<?php echo Yii::$app->urlManager->createUrl('/checkin/default/checkin'); ?>',
            'data': {'member_id':member_id,membership_type_id:membership_type_id,membership_id:membership_id,member_trainings:member_trainings},
            success:function(data){     
                $('#enter_success').focus();
                swal({
                    title:'<?php echo Yii::t('app',"Checked in successfully"); ?>',
                    type:'success'
                },function(){
                    location.reload();
                });
                hideModel('bs-model-checkin'); 
				$.unblockUI();
//                reloadGridview();
                if(tour_step=='<?php echo app\models\Config::TOUR_CHECKIN; ?>')
                {
                    $('#bs-model-checkin-endtour').modal('show');
                }
                $('#enter_success').focus();
                $('#enter_success').keypress(function(event){
                    var keycode1 = (event.keyCode ? event.keyCode : event.which);
                    if (keycode1 == 13) {
                        $('.confirm.btn').click();
                    }
                });
            }
        });
    }
    function checkout(member_id,checkin_id){
		$.blockUI();
        $.ajax({
            'type':'POST',
            'url':'<?php echo Yii::$app->urlManager->createUrl('/checkin/default/checkout'); ?>',
            'data': {'member_id':member_id,checkin_id:checkin_id},
            success:function(data){
                $('#enter_success').focus();
                swal({
                    title:'<?php echo Yii::t('app',"Checked out successfully"); ?>',
                    type:'success'
                },function(){
                    location.reload();
                });
                hideModel('bs-model-checkin');
				$.unblockUI();
//                reloadGridview();
                if(tour_step=='<?php echo app\models\Config::TOUR_CHECKIN; ?>')
                {
                    $('#bs-model-checkin-endtour').modal('show');
                }
                $('#enter_success').focus();
                $('#enter_success').keypress(function(event){
                    var keycode1 = (event.keyCode ? event.keyCode : event.which);
                    if (keycode1 == 13) {
                        $('.confirm.btn').click();
                    }
                });
            }
        });
    }
    
    function reloadGridview(){
        var keysearch = $('#txt_card_id').val();
        location.href="index?key="+keysearch;
//        $("#view_only_checkin").load("index",{keysearch:keysearch,ajax:1});
    }

    function checkoutSelected(){
        var selected_id = [];
        $.each($("input[name='selection[]']:checked"), function(){
            selected_id.push($(this).val());
        });
        if(selected_id.length<=0){
            alert('<?php echo Yii::t('app','Please, select a membership.'); ?>');
            return;
        }
        swal({
                title: "<?php echo Yii::t('app','Are you sure you want to checkout the membership cards?'); ?>",
                type: "warning",
                showLoaderOnConfirm: true,
                showCancelButton: true,
                closeOnConfirm: false,
                confirmButtonText: "<?php echo Yii::t('app','OK'); ?>",
                cancelButtonText: "<?php echo Yii::t('app','Cancel'); ?>"
                }, function () {
                    $.blockUI();
                    $.ajax({
                        'type':'POST',
                        'url':'<?php echo Yii::$app->urlManager->createUrl('/checkin/default/checkout-select'); ?>',
                        data:{checkin_selected_id:selected_id},
                        success:function(data){
                            $('#enter_success').focus();
                            swal({
                                title:'<?php echo Yii::t('app',"Checked out successfully"); ?>',
                                type:'success'
                            },function(){
                                location.reload();
                            });
                            $.unblockUI();
                            $('#enter_success').focus();
                            $('#enter_success').keypress(function(event){
                                var keycode1 = (event.keyCode ? event.keyCode : event.which);
                                if (keycode1 == 13) {
                                    $('.confirm.btn').click();
                                }
                            });
                        }
                    });
            });
		
    }
    
</script>