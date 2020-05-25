<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\course\models\CourseSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Courses');

$keyserch = "";
//Check permission
$m = 'course';
$BasicPermission = new \app\modules\permission\models\BasicPermission();
$canAdd = $BasicPermission->checkModules($m, 'add');
$canUpdate = $BasicPermission->checkModules($m, 'update');
$canView = $BasicPermission->checkModules($m, 'view');
//End check permission
if($canAdd)
	$add = "<div class='parkclub-rectangle-header-right' ><button id='btn-add-facility' onclick='window.location.href=\"".Yii::$app->urlManager->createUrl('/course/default/create')."\"'>".Yii::t('app', 'NEW COURSE')."</button></div>";
else 
	$add = '';
?>
<div class="parkclub-rectangle-content">
<?php Pjax::begin(); ?> 
<?= GridView::widget([
        'dataProvider' => $dataProvider,
//                        'filterModel' => $searchModel,
        'id'=>'gridview-course',
        'tableOptions' => ['class' => 'parkclub-check-table'],
        'summary' => "<div class='parkclub-rectangle-header'>"
                . "<div class='parkclub-rectangle-header-left'>".Yii::t('app',"Showing <b>{begin} - {end}</b> of <b>{totalCount}</b> items")."</div>"
                . $add
            . "</div>",
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute'=>'course_name',
                'format'=>'raw',
                'value'=>function($model) use ($canView){
                    if($canView)
						return yii\bootstrap\Html::a($model->course_name, Yii::$app->urlManager->createUrl('/course/default/view?id='.$model->course_id));
					else 
						return $model->course_name;
				}
            ],
            'course_content:ntext',
            [
                'attribute'=>'course_amount',
                'format'=>'raw',
                'value'=>function($model){
                    return $model->getDisplayAmount();
                }
            ],
            [
                'attribute'=>'course_status',
                'format'=>'raw',
                'value' => function($model) use ($canUpdate) {
					if($canUpdate) {
						if($model->course_status == 1)
							return '<a href="#" onclick="updateStatus('.$model->course_id.',0);return false;"><i class="glyphicon glyphicon-ok"></i></a>';
						else
							return '<a href="#" onclick="updateStatus('.$model->course_id.',1);return false;"><i class="glyphicon glyphicon-remove"></i></a>';
					} else {
						if($model->course_status == 1)
							return '<a href="#" onclick="alert(&quot;You do not have permission with this action.&quot;);"><i class="glyphicon glyphicon-ok"></i></a>';
						else
							return '<a href="#" onclick="alert(&quot;You do not have permission with this action.&quot;);"><i class="glyphicon glyphicon-remove"></i></a>';
					}
				},

            ],      

            ['class' => 'yii\grid\ActionColumn',
                'template'=>'{update} {delete}'],
        ],
    ]); ?>  
<?php Pjax::end(); ?>
</div>
<script type="text/javascript">
    function updateStatus(id,status){
        $.ajax({
            type:'POST',
            url:'<?php echo Yii::$app->urlManager->createUrl('course/default/update-status'); ?>',
            data:{id:id,status:status},
            success:function(data){
                data = jQuery.parseJSON(data);
                if(data.status=='success'){
                    $.notify("<?php echo Yii::t('app', 'successfully saved') ?>", "success");
                    $('#gridview-course').yiiGridView("applyFilter");
                }
            }
        });
    }
</script>