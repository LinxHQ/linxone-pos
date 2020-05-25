<?php

use yii\helpers\Html;
use yii\grid\GridView;

//Check permission 
$m = 'facility';
$BasicPermission = new \app\modules\permission\models\BasicPermission();
$canList = $BasicPermission->checkModules($m, 'list');
$canAdd = $BasicPermission->checkModules($m, 'add');
$canUpdate = $BasicPermission->checkModules($m, 'update');
$canDelete = $BasicPermission->checkModules($m, 'delete');
$canView = $BasicPermission->checkModules($m, 'view');

if(!$canList){
    echo "You don't have permission with this action.";
    return false;
}
//End check permission

/* @var $this yii\web\View */
/* @var $searchModel app\models\FacilitySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Facilities');
//$this->params['breadcrumbs'][] = $this->title;
$keyserch = "";
$add = "<div class='parkclub-rectangle-header-right' ><button id='btn-add-facility' onclick='window.location.href=\"".Yii::$app->urlManager->createUrl('/facility/default/create')."\"'>".Yii::t('app', 'NEW FACILITY')."</button></div>";
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
                        . "<div class='parkclub-rectangle-header-left'>".Yii::t('app',"Showing <b>{begin} - {end}</b> of <b>{totalCount}</b> items")."</div>"
                        . $add
                    . "</div>",
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

        //            'facility_id',
                    [
                        'attribute' => 'facility_name',
                        'format'=>'raw',
                        'value'=>function($model){
                            return '<a href ="'.YII::$app->urlManager->createUrl("facility/default/view?id=".$model->facility_id).'">'.$model->facility_name.'</a>';
                        }
                    ],
                    [
                        'attribute' => 'facility_free',
						'header' => Yii::t('app','Status'),
                        'format'=>'raw',
                        'value'=>function($model){
                            return \app\models\ListSetup::getItemByList('status_pay')[$model->facility_free];
                        }
                    ],
                    'facility_description:ntext',
                    [
                        'attribute' => 'facitily_status',
                        'format'=>'raw',
                        'value'=>function($model,$canUpdate){
                            $m = 'facility';
                            $BasicPermission = new \app\modules\permission\models\BasicPermission();
                            $canEdit = $BasicPermission->checkModules($m, 'update');
                            if($canEdit){
                                if($model->facitily_status==1)
                                    return '<a href="#" onclick="updateStatus('.$model->facility_id.',0);return false;"><span class="glyphicon glyphicon-ok"></span></a>';
                                else
                                    return '<a href="#" onclick="updateStatus('.$model->facility_id.',1);return false;"><span class="glyphicon glyphicon-remove"></span></a>';
                            }else
                            {
                                if($model->facitily_status==1)
                                    return '<span class="glyphicon glyphicon-ok"></span>';
                                else
                                    return '<span class="glyphicon glyphicon-remove"></span>'; 
                            }
                        }
                    ],
        //            ['class' => 'yii\grid\ActionColumn'],
                ],
            ]); ?>  
        </div>
    </div>
</div>
<script type="text/javascript">
    $( document ).ready(function() {
        var tour_step = '<?php echo Yii::$app->session['tour-step']; ?>';
        if(tour_step=='<?php echo app\models\Config::TOUR_FACILITY; ?>')
        {
            tour_facility.restart();
            tour_facility.start();
        }
    });
    function updateStatus(facility_id,status){
        $.ajax({
            'type':'POST',
            'url':'<?php echo YII::$app->urlManager->createUrl('facility/default/updatestatus'); ?>',
            'data':{'facility_id':facility_id,status:status},
            success:function(data){
                location.reload();
            }
        });
    }
</script>
