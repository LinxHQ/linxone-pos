<?php
use yii\helpers\Html;
use yii\grid\GridView;

use yii\helpers\Url;
use kartik\tabs\TabsX;

$this->title = Yii::t('app', 'Configuration');
?>
<div class="parkclub-subtop-bar">
    <div class="parkclub-nameplate"><div class="parkclub-iconbg"><img src="<?php echo Yii::$app->urlManager->baseUrl; ?>/image/park_new/types.png" alt=""></div> <h3><?php echo $this->title; ?></h3></div>
    <div class="parkclub-search"> </div>
</div>

<div class="parkclub-wrapper parkclub-wrapper-search ">
    <div class="parkclub-rectangle parkclub-shadow">
        <div class="parkclub-rectangle-content">
            <div  class="parkclub-nameplate"><i class="glyphicon glyphicon-inbox" style="font-size: 16px;"></i> <a id="cog-membership-type" href="<?php echo Yii::$app->urlManager->createUrl('/membership_type/default/index'); ?>"><?php echo Yii::t('app', 'MemberShip Type'); ?></a></div>
            <div class="parkclub-nameplate"><i class="glyphicon glyphicon-inbox" style="font-size: 16px;"></i> <a href="<?php echo Yii::$app->urlManager->createUrl('/revenue_type/default/index'); ?>"><?php echo Yii::t('app', 'Revenue types'); ?></a></div>
            <div class="parkclub-nameplate"><i class="glyphicon glyphicon-inbox" style="font-size: 16px;"></i> <a href="<?php echo Yii::$app->urlManager->createUrl('/listsetup/index'); ?>"><?php echo Yii::t('app', 'List setup'); ?></a></div>
            <div class="parkclub-nameplate"><i class="glyphicon glyphicon-inbox" style="font-size: 16px;"></i> <a href="<?php echo Yii::$app->urlManager->createUrl('/site/setting'); ?>"><?php echo Yii::t('app', 'Setting System'); ?></a></div>
            <div class="parkclub-nameplate"><i class="glyphicon glyphicon-inbox" style="font-size: 16px;"></i> <a href="<?php echo Yii::$app->urlManager->createUrl('/pos/default/setting'); ?>"><?php echo Yii::t('app', 'Point of sale'); ?></a></div>
            <br>
        </div>
    </div>
</div>
<script type="text/javascript">
    $( document ).ready(function() {
        var tour_step = '<?php echo Yii::$app->session['tour-step']; ?>';
        if(tour_step=='<?php echo app\models\Config::TOUR_MEMBERSHIP_TYPE; ?>')
        {
            tour_membership_type.restart();
            tour_membership_type.start();
        }
    });
</script>