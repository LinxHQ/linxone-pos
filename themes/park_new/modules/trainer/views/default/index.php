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
$m = 'trainer';
$canAdd = $BasicPermission->checkModules($m, 'add');
$host_server = explode('.', $_SERVER['HTTP_HOST']);
?>
<div class="parkclub-subtop-bar">
    <div class="parkclub-nameplate"><div class="parkclub-iconbg"><img src="<?php echo Yii::$app->urlManager->baseUrl; ?>/image/park_new/members.png" alt=""></div> <h3><?php echo Yii::t('app','Trainers');?></h3></div>
    <div class="parkclub-search">
        <input id="search_member" type="text" placeholder="<?php echo Yii::t('app','By Name or Mobile');?>">
        <button class="parkclub-searchbtn parkclub-searchbtn-2" type="submit" onclick="search_member();return false;"><img src="<?php echo Yii::$app->urlManager->baseUrl; ?>/image/park_new/search.png"></button>
    </div>
</div>




<div  id="members-index" class="parkclub-wrapper parkclub-wrapper-search members-index">
    <?= $this->render('_index', [
        'dataProvider' => $dataProvider,
    ]) ?>
</div>
<script>
    $('#search_member').focus();
    (function($) {
        $('#search_member').keypress(function(event){
            var keycode = (event.keyCode ? event.keyCode : event.which);
            if (keycode == '13') {
              search_member();
            }
        });
        
    })(jQuery);
    $( document ).ready(function() {
        var tour_step = '<?php echo Yii::$app->session['tour-step']; ?>';
        if(tour_step=='<?php echo app\models\Config::TOUR_TRAINER; ?>')
        {
            tour_trainer.restart();
            tour_trainer.start();
        }
    });
    function search_member()
    {
        var search_member=$('#search_member').val();
        
        var search_member_nmuber = search_member.replace(/\s/g,'');
        
        if(isNaN(search_member_nmuber) == false)
            $(".members-index").load("searchmember",{search_member:search_member,search_member_nmuber:search_member_nmuber});
        else
            $(".members-index").load("searchmember",{search_member:search_member});
    }
    function trimSpace(str) {
    return str.replace(/^\s*/, "").replace(/\s*$/, "");
    }
</script>



