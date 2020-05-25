<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\MembersCheckinSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

//$this->title = Yii::t('app', 'Members Checkins');
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="parkclub-rectangle parkclub-shadow">
            <div class="parkclub-rectangle-content">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?php \yii\widgets\Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
        'layout' => '{items}{pager}',
//        'headerRowOptions'=>['style'=>'font-weight:bold;',],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'header' => Yii::t('app','Card ID'),
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
                'header' => Yii::t('app','Picture'),
                'attribute' => 'member_picture',
                'format' => 'raw',
                'value' => function($model) {
                    return '<img class="img-circle" width="80" src="'.app\modules\members\models\Members::getMemberImages($model->member_id).'" />';
                }
            ],
            [
                'header' => Yii::t('app','Name'),
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
                'header' => Yii::t('app','Checkin'),
                'attribute'=>'checkin_time',
                'format' => 'raw',
                'value' => function($model) {
                    return date(YII::$app->params['defaultDateTime'],  strtotime($model->checkin_time));
                }
            ],
            [
                'header' => Yii::t('app','Checkout'),
                'format' => 'raw',
                'value' => function($model) {
                    if($model->checkcout_time!="0000-00-00 00:00:00")
                        return date(YII::$app->params['defaultDateTime'],strtotime($model->checkcout_time));
                    else
                        return "";
                }
            ],
            [
                'header' => Yii::t('app','Membership Type'),
                'format' => 'html',
                'value' => function($model) {
                    $membershipType = \app\modules\membership_type\models\MembershipType::findOne($model->membership_type_id);
                    if($membershipType->membership_name)
                        return $membershipType->membership_name;
                    return '';
                }
            ],
                    ],
    ]); ?>
    <?php \yii\widgets\Pjax::end(); ?>
</div>
</div>