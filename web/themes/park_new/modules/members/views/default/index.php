<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\modules\membership_type;
use app\modules\members\models\Membership;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\members\models\memberSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

//Call permisstion
$BasicPermission = new app\modules\permission\models\BasicPermission();
$m = 'members';
$canAdd = $BasicPermission->checkModules($m, 'add');
$canView = $BasicPermission->checkModules($m, 'view');
$canUpdate = $BasicPermission->checkModules($m, 'update');
$canDelete = $BasicPermission->checkModules($m, 'delete');
$canList = $BasicPermission->checkModules($m, 'list');

//Check permission 
if(!$canList){
    echo Yii::t('app',"You don't have permission with this action.");
    return;
}

?>
<div class="parkclub-subtop-bar">
    <div class="parkclub-nameplate"><div class="parkclub-iconbg"><img src="<?php echo Yii::$app->urlManager->baseUrl; ?>/image/park_new/members.png" alt=""></div> <h3><?php echo Yii::t('app','Members') ?></h3></div>
    <div class="parkclub-search">
        <input id="search_member" type="text" placeholder="<?php echo Yii::t('app','Enter name or mobile to search');?>">
        <button class="parkclub-searchbtn parkclub-searchbtn-2" type="submit" onclick="search_member();return false;"><img src="<?php echo Yii::$app->urlManager->baseUrl; ?>/image/park_new/search.png"></button>
    </div>
</div>

<div id="members-index" class="parkclub-wrapper parkclub-wrapper-search ">
    <?= $this->render('_index', [
        'searchModel'=>$searchModel,
        'dataProvider' => $dataProvider,
        'status'=>\app\modules\members\models\Members::STATUS_MEMBER_ACTIVATED
    ]) ?>
    
</div>

<div id="members-index-deactive" class="parkclub-wrapper parkclub-wrapper-search ">
    <?= $this->render('_index', [
        'status'=> \app\modules\members\models\Members::STATUS_MEMBER_DEACTIVATE,
        'dataProvider' => $dataProviderDeactive,
    ]) ?>
    
</div>

<script>
    $( document ).ready(function() {
        var intall_data = '<?php echo Yii::$app->session['install-data']; ?>';
        var istour = '<?php echo Yii::$app->session['tour']; ?>';
        if((intall_data==2 || intall_data==1) && istour==1){
            tour_no_demo.restart();
            tour_no_demo.start();
            tour_no_demo.goTo(7);
        }
        var tour_step = '<?php echo Yii::$app->session['tour-step']; ?>';
        if(tour_step=='<?php echo app\models\Config::TOUR_MEMBER; ?>')
        {
            tour_member.restart();
            tour_member.start();
        }
    });
    $('#search_member').focus();
    (function($) {
        $('#search_member').keypress(function(event){
            var keycode = (event.keyCode ? event.keyCode : event.which);
            if (keycode == '13') {
              search_member();
            }
        });
       
    })(jQuery);
    function search_member()
    {
        var search_member=$('#search_member').val();
        
        var search_member_nmuber = search_member.replace(/\s/g,'');
        $.blockUI() ;
		if(isNaN(search_member_nmuber) == false){
            $("#members-index").load("searchmember",{search_member:search_member,search_member_nmuber:search_member_nmuber,status:'<?php echo \app\modules\members\models\Members::STATUS_MEMBER_ACTIVE; ?>'},function(){$.unblockUI();});
            $("#members-index-deactive").load("searchmember",{search_member:search_member,search_member_nmuber:search_member_nmuber,status:'<?php echo \app\modules\members\models\Members::STATUS_MEMBER_DEACTIVATE; ?>'});
		}
        else{
            $("#members-index").load("searchmember",{search_member:search_member,status:'<?php echo \app\modules\members\models\Members::STATUS_MEMBER_ACTIVE; ?>'},function(){$.unblockUI();});
            $("#members-index-deactive").load("searchmember",{search_member:search_member,status:'<?php echo \app\modules\members\models\Members::STATUS_MEMBER_DEACTIVATE ?>'});
		}
    }
    function trimSpace(str) {
    return str.replace(/^\s*/, "").replace(/\s*$/, "");
    }
</script>