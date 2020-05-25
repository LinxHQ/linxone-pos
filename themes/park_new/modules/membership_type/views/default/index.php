<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\models\ListSetup;
use app\modules\members\models\Membership;
use app\assets\AppAsset;
$m = 'membership_type';
$BasicPermission = new \app\modules\permission\models\BasicPermission();
$DefinePermission = new \app\modules\permission\models\DefinePermission();

$canAdd = $BasicPermission->checkModules($m, 'add');
$canList = $BasicPermission->checkModules($m, 'list');
$canEdit = $BasicPermission->checkModules($m, 'edit');
$canView = $BasicPermission->checkModules($m, 'view');
$canDelete = $BasicPermission->checkModules($m, 'delete');

if(!$canList){
    echo Yii::t('app',"You don't have permission with this action.");
    return ;
}

/* @var $this yii\web\View */
/* @var $searchModel app\modules\membership_type\models\MembershipTypeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = Yii::t('app', 'MemberShip Type');

?>
<div class="parkclub-subtop-bar">
    <div class="parkclub-nameplate"><div class="parkclub-iconbg"><img src="<?php echo Yii::$app->urlManager->baseUrl; ?>/image/park_new/types.png" alt=""></div> <h3><?php echo $this->title; ?></h3></div>
    <div class="parkclub-search">
        <input id="search_value" type="text" placeholder="<?php echo Yii::t('app','Enter key');?>" id="txt_card_id" value="">
        <button class="parkclub-searchbtn search-1" onclick="search_membership_type();" type="submit"><img src="<?php echo Yii::$app->urlManager->baseUrl; ?>/image/park_new/search.png"></button>
    </div>
</div>

<div class="parkclub-wrapper parkclub-wrapper-search ">
    <div class="parkclub-rectangle parkclub-shadow">
        <div class="parkclub-rectangle-content membership-type">
            <?php echo $this->render('_search', ['searchModel' => $searchModel,'dataProvider' => $dataProvider,]); ?>
        </div>
    </div>
</div>
<script>

$('#search_value').focus();

$( document ).ready(function() {
    $('#search_value').keypress(function(event){
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if (keycode == '13') {
          search_membership_type();
        }
    });
    
    var intall_data = '<?php echo Yii::$app->session['install-data']; ?>';
    var tour = '<?php echo Yii::$app->session['tour']; ?>';
    if(intall_data==2 && tour==1){
        tour_no_demo.restart();
        tour_no_demo.start();
        tour_no_demo.goTo(1);
    }
    var tour_step = '<?php echo Yii::$app->session['tour-step']; ?>';
    if(tour_step=='<?php echo app\models\Config::TOUR_MEMBERSHIP_TYPE; ?>')
    {
        tour_membership_type.restart();
        tour_membership_type.start();
        tour_membership_type.goTo(2)();
    }
});
function search_membership_type()
{
    var search_value = $('#search_value').val();
    search_value=trimSpace(search_value);
    $('.membership-type').load('search',{search_value:search_value});
}

function trimSpace(str) {
        return str.replace(/^\s*/, "").replace(/\s*$/, "");
    }
</script>