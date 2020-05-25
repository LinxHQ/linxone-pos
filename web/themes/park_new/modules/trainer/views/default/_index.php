<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\modules\membership_type;
use app\modules\members\models\Membership;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\members\models\memberSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


$BasicPermission = new app\modules\permission\models\BasicPermission();
$m = 'trainer';
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


$add_trainer = "";
if($canAdd){
    $add_trainer = "<div class='parkclub-rectangle-header-right'><button id='btn-add-trainer' onclick='window.location.href=\"".Yii::$app->urlManager->createUrl('/trainer/default/create')."\"'>".Yii::t('app', 'ADD TRAINER')."</button></div>";
}

?>
<div class="parkclub-rectangle parkclub-shadow">
            <div class="parkclub-rectangle-content">

<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
//        'layout'=>"{items}\n{pager}",
        'summary' => "<div class='parkclub-rectangle-header'>"
                        . "<div class='parkclub-rectangle-header-left'>".Yii::t('app',"Showing <b>{begin} - {end}</b> of <b>{totalCount}</b> items")."</div>"
                        . $add_trainer
                    . "</div>",
        'tableOptions' => ['class' => 'parkclub-check-table'],
        'columns' => [
            [
	            'attribute' => 'member_name',
	            'format' => 'html',
                    'header' => Yii::t('app','Name'),
	            'value' => function($model) {
                        return "<a href='".Yii::$app->urlManager->createUrl('trainer/default/update?id='.$model->member_search_id)."'>".$model->surname." ".$model->first_name."</a>";
	            }
	    ],
            [
	            'attribute' => 'trainer_code',
	            'format' => 'html',
                    'header' => Yii::t('app','Code'),
	    ],     
//            [
//	            'attribute' => 'membership_code',
//	            'format' => 'html',
//	            'header' => Yii::t('app','Card No'),
//	            'value' => function($model) {
//                        return $model->membership_code;
//	            }
//	    ],
            [
	            'attribute' => 'member_mobile',
	            'format' => 'html',
                    'header'=>Yii::t('app','Mobile'),
	            'value' => function($model) {
                        return $model->member_mobile;
	            }
	    ],
            [
	            'attribute' => 'member_email',
	            'format' => 'html',
                    'header'=>'Email',
	    ],      
//            [
//	            'attribute' => 'id_card',
//	            'format' => 'html',
//	            'header' => Yii::t('app','Identity Card'),
//	            'value' => function($model) {
//                        return $model->member_card_id;
//	            }
//	    ],
                    
            ['class' => 'yii\grid\ActionColumn',
                'template' => '{delete}',
                'buttons' => [
                     'delete' => function ($url, $key, $index) {
                        $member = \app\modules\members\models\Members::findOne($key->member_search_id);
                        if($member->isCanDeleteTrainer()){
                            $url = YII::$app->urlManager->createUrl('/trainer/default/delete?id='.$key->member_search_id);
                            return Html::a('<span class="glyphicon glyphicon-trash" ></span>', $url, [
                                'class' => '',
                                'data' => [
                                    'confirm' => Yii::t('app','Are you sure you want to delete this item?'),
                                    'method' => 'post',
                                ],
                            ]);
                        }
                        return "";
                    },
                ], 
            ]
            
        ],
    
    ]); ?>
<?php Pjax::end(); ?>
</div>
    <div class="parkclub-footer"></div>
</div>
