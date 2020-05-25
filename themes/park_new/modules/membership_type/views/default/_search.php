<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\models\ListSetup;
use app\modules\members\models\Membership;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\membership_type\models\MembershipTypeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$m = 'membership_type';
$BasicPermission = new \app\modules\permission\models\BasicPermission();
$DefinePermission = new \app\modules\permission\models\DefinePermission();

$canAdd = $BasicPermission->checkModules($m, 'add');

if($canAdd){
    $add = "<div class='parkclub-rectangle-header-right'><button id='tour-add-membership-type' onclick='window.location.href=\"".Yii::$app->urlManager->createUrl('/membership_type/default/create')."\"'>".Yii::t('app','ADD MEMBERSHIP TYPE')."</button></div>";
}

?>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'layout'=>"{items}\n{pager}",
        'tableOptions' => ['class' => 'parkclub-check-table'],
        'summary' => "<div class='parkclub-rectangle-header'>"
                        . "<div class='parkclub-rectangle-header-left'>".Yii::t('app',"Showing <b>{begin} - {end}</b> of <b>{totalCount}</b> items")."</div>"
                        . $add
                    . "</div>",
        'columns' => [

            [
	            'attribute' => 'membership_name',
	            'format' => 'html',
	            'value' => function($model) {
                        return "<a href='".Yii::$app->urlManager->createUrl('membership_type/default/update?id='.$model->membership_type_id)."'>".$model->membership_name."</a>";
	            }
	    ],
            
            'membership_description:ntext',
//            [
//                     'attribute' => 'membership_type',
//                     'format' => 'html',
//                     'value' => function($model) {
//                         $ListSetup = new ListSetup();
//                         $arr_membershipType= $ListSetup->membership_type;
//                         return $arr_membershipType[$model->membership_type];
//                     }
//             ],
            [
                     'attribute' => 'membership_type_id',
                     'format' => 'html',
                     'value' => function($model) {
                        $infoPrice = new app\modules\membership_type\models\MembershipPrice();
                        $info_price =$infoPrice->getPriceByMembershipType($model->membership_type_id);
                        $price=0;
                        if($info_price[0]['membership_price'])
                            $price = $info_price[0]['membership_price'];
                        $price= number_format($price,2);
                        return '$'.$price;
                     }
             ],
            [
                     'attribute' => 'membership_type_id',
                     'format' => 'html',
                     'header' => Yii::t('app', 'Period'),
                     'value' => function($model) {
                        $memberShipTypePrice = new \app\modules\membership_type\models\MembershipPrice();
                        return $memberShipTypePrice->getDateMemberTypeApplyPrice($model->membership_type_id);
                     }
             ], 
            [
                     'attribute' => 'membership_type_month',
                     'format' => 'html',
                     'value' => function($model) {
                         $ListSetup = new ListSetup();
                         $arr_membershipType= ListSetup::getItemByList('memberShipType_status');
                         return (($model->membership_type_month!=0) ? $arr_membershipType[$model->membership_type_month] : "");
                     }
             ],
            [
                     'attribute' => 'membership_status',
                     'format' => 'html',
                     'value' => function($model) {
                         return Yii::t('app',$model->membership_status);
                     }
             ],
                     
            ['class' => 'yii\grid\ActionColumn',
                'template' => '{delete}',
                'buttons' => [
                     'delete' => function ($model, $key, $index) {
                        $urlConfig = [];
                       
                        $membersShipInfo = Membership::findOne(['membership_type_id'=>$key->membership_type_id]);
                        
                        $url = $model;
                        if($membersShipInfo)
                        {
                            return Html::a('<span class="glyphicon glyphicon-trash"></span>', "", [
                                'class' => '',
                                'data' => [
                                    'confirm' => Yii::t('app', 'This membership type has already had a membership, so you can not delete it'),
//                                    'method' => 'post',
                                ],
                            ]);
                        }
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                            'class' => '',
                            'data' => [
                                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                                'method' => 'post',
                            ],
                        ]);
                    },
                ],
            ],
                     
            
        ],
    
    ]); ?>
<?php Pjax::end(); ?>