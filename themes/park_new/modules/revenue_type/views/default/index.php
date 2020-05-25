<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\revenue_type\models\RevenueSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

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

$this->title = Yii::t('app', 'Revenue types');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="parkclub-subtop-bar">
    <div class="parkclub-nameplate"><div class="parkclub-iconbg"><img src="<?php echo Yii::$app->urlManager->baseUrl; ?>/image/park_new/types.png" alt=""></div> <h3><?php echo $this->title; ?></h3></div>
    <div class="parkclub-search">
        <input id="search_value" type="text" placeholder="<?php echo Yii::t('app','Enter key');?>" id="txt_card_id" value="">
        <button class="parkclub-searchbtn search-1" onclick="search_revenue_type();" type="submit"><img src="<?php echo Yii::$app->urlManager->baseUrl; ?>/image/park_new/search.png"></button>
    </div>
</div>

<div class="parkclub-wrapper parkclub-wrapper-search ">
    <div class="parkclub-rectangle parkclub-shadow">
        <div class="parkclub-rectangle-content revenue-type">
            <?php echo $this->render('_search', ['searchModel' => $searchModel,'dataProvider' => $dataProvider,]); ?>
        </div>
    </div>
</div>

<script>
$('#search_value').focus();
(function($) {
    $('#search_value').keypress(function(event){
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if (keycode == '13') {
          search_revenue_type();
        }
    });

})(jQuery);
function search_revenue_type()
{
    var search_value = $('#search_value').val();
    search_value=trimSpace(search_value);
    $('.revenue-type').load('search',{search_value:search_value});
}

function trimSpace(str) {
        return str.replace(/^\s*/, "").replace(/\s*$/, "");
    }
</script>