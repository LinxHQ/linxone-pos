<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\modules\members\models\Members;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\comment\models\CommentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

//Check permission 
//$m = 'comment';
//$BasicPermission = new \app\modules\permission\models\BasicPermission();
//$canList = $BasicPermission->checkModules($m, 'list');
//$canAdd = $BasicPermission->checkModules($m, 'add');
//$canUpdate = $BasicPermission->checkModules($m, 'update');
//$canDelete = $BasicPermission->checkModules($m, 'delete');
//$canView = $BasicPermission->checkModules($m, 'view');
//
//if(!$canList){
//    echo "You don't have permission with this action.";
//    return false;
//}
//End check permission

$this->title = Yii::t('app', 'Comments');
//$this->params['breadcrumbs'][] = $this->title;

$keyserch = "";
$add = "<div class='parkclub-rectangle-header-right' ><button id='btn-add-facility' onclick='window.location.href=\"".Yii::$app->urlManager->createUrl('/comment/comment/create')."\"'>".Yii::t('app', 'NEW COMMENT')."</button></div>";
?>
<div class="parkclub-subtop-bar">
    <div class="parkclub-nameplate"><div class="parkclub-iconbg"><img src="<?php echo Yii::$app->urlManager->baseUrl; ?>/image/park_new/facilities.png" alt=""></div> <h3><?php echo $this->title; ?></h3></div>
    <div class="parkclub-search">
    </div>
</div>

<div class="parkclub-wrapper parkclub-wrapper-search">
    <div class="parkclub-rectangle parkclub-shadow">
        <div class="parkclub-rectangle-content">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
        //        'filterModel' => $searchModel,
                'tableOptions' => ['class' => 'parkclub-check-table'],
                'summary' => "<div class='parkclub-rectangle-header'>"
                        . "<div class='parkclub-rectangle-header-left'>".Yii::t('app',"Showing <b>{begin} - {end}</b> of <b>{totalCount}</b> items")."</div></div>",
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

        //            'facility_id',
                    [
                        'attribute' => 'comment_content',
                        'header' => Yii::t('app','Content'),
                        'format'=>'raw',
                        'value'=>function($model){
                            return '<a href ="'.YII::$app->urlManager->createUrl("comment/comment/comment-detail?comment_id=".$model->comment_id).'">'.$model->comment_content.'</a>';
                        }
                    ],
                    [
                        'attribute' => 'comment_create_date',
			'header' => Yii::t('app','Create date'),
                        'format'=>'raw',
                        'value'=>function($model){
                            return $model->comment_create_date;
                        }
                    ],
                    [
                        'attribute' => 'comment_status',
			'header' => Yii::t('app','Tên khách hàng'),
                        'format'=>'raw',
                        'value'=>function($model){
                            $member = new Members();
                  
                            return $member->getMemberFullName($model->comment_create_by);
                        }
                    ],
                ],
            ]); ?>  
        </div>
    </div>
</div>
<script>
    
</script>

