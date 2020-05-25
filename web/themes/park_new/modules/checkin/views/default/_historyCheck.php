<?php

use yii\helpers\Html;
use yii\grid\GridView;

?>
<div class="parkclub-subtop-bar">
    <div class="parkclub-nameplate"><div class="parkclub-iconbg"><img src="<?php echo Yii::$app->urlManager->baseUrl; ?>/image/park_new/check.png" alt=""></div> <h3>
        <?php echo Yii::t('app','History check in / out');?></h3>
                </div>
    <div class="parkclub-search">
        <a href="<?php echo YII::$app->urlManager->createUrl('/checkin/default/index'); ?>" class="btn btn-default"><?php echo Yii::t('app', 'Back');?></a>
    </div>
</div>
<div class="parkclub-wrapper parkclub-wrapper-search">
    <div class="parkclub-rectangle parkclub-shadow">
        <div class="parkclub-rectangle-content">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'summary' => "<div class='parkclub-rectangle-header'>"
                                . "<div class='parkclub-rectangle-header-left'>".Yii::t('app',"Showing <b>{begin} - {end}</b> of <b>{totalCount}</b> items")."</div>"
                            . "</div>",
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    [
                        'header' => Yii::t('app', 'Card ID'),
                        'attribute' => 'membership_code',
                        'format' => 'html',
                        'value' => function($model) {
                            $membership = app\modules\members\models\Membership::findOne($model->membership_id);
                            if($membership)
                                return $membership->membership_code;
                            return "";
                        }
                    ],
                    [
                        'header'=>Yii::t('app','Member Picture'),
                        'attribute' => 'member_picture',
                        'format' => 'html',
                        'value' => function($model) {
                            return '<img style="margin-top:5px;" width="80" src="'.app\modules\members\models\Members::getMemberImages($model->member_id).'" />';
                        }
                    ],
                    [
                        'header'=>Yii::t('app','Member Name'),
                        'attribute' => 'member_name',
                        'format' => 'html',
                        'value' => function($model) {
                            $member = app\modules\members\models\Members::findOne($model->member_id);
                            if($member)
                                return $member->getMemberFullName();
                            return "";
                        }
                    ],
                    [   
                        'header' => Yii::t('app', 'Checkin'),
                        'attribute'=>'checkin_time',
                        'format' => 'raw',
                        'value' => function($model) {
                            return date(YII::$app->params['defaultDateTime'],  strtotime($model->checkin_time));
                        }
                    ],
                    [
                        'header' => Yii::t('app', 'Checkout'),
                        'format' => 'raw',
                        'value' => function($model) {
                            if($model->checkcout_time!="0000-00-00 00:00:00")
                                return date(YII::$app->params['defaultDateTime'],strtotime($model->checkcout_time));
                            else
                                return "";
                        }
                    ],
                    [
                        'header' => Yii::t('app', 'Expiry'),
                        'format' => 'html',
                        'value' => function($model) {
                            $membership = app\modules\members\models\Membership::findOne($model->membership_id);
                            if($membership)
                                return date(YII::$app->params['defaultDate'],strtotime($membership->membership_enddate));
                            return "";
                        }
                    ],
                    [
                        'header' => Yii::t('app', 'Membership Type'),
                        'format' => 'html',
                        'value' => function($model) {
                            $membershipType = \app\modules\membership_type\models\MembershipType::findOne($model->membership_type_id);
                            if($membershipType)
                                return $membershipType->membership_name;
                            return '';
                        }
                    ],
                            ],
            ]); ?>
        </div>
    </div>
</div>