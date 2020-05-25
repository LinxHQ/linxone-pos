<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\course\models\ClasscSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Classcs');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="classc-index">
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
        'summary'=>false,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute'=>'class_name',
                'format'=>'raw',
                'value'=>function($model){
                    return yii\bootstrap\Html::a($model->class_name,Yii::$app->urlManager->createUrl('/course/classc/view?id='.$model->class_id));
                } 
            ],
            [
                'attribute'=>'teacher_id',
                'format'=>'raw',
                'value'=>function($model){
                    return $model->getTeacher();
                }
            ],
            'class_content:ntext', 
            [
                'attribute'=>'class_start_date',
                'format'=>'raw',
                'value'=>function($model){
                    return \app\models\ListSetup::getDisplayDate($model->class_start_date);
                }
            ],
            [
                'attribute'=>'class_schedule',
                'format'=>'raw',
                'value'=>function($model){
                    return $model->getSchedule('string').'<br>'.
                    \app\models\ListSetup::getDisplayTime($model->class_start_time)." - ".\app\models\ListSetup::getDisplayTime($model->class_end_time);
                }
            ],     
             'class_number_session',
            // 'class_status',
            // 'class_created_by',

            [
                'class' => 'yii\grid\ActionColumn',
                'template'=>'{update} {delete}'
                ],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>

<script type="text/javascript">
    function loadSessionClass(class_id,class_name){
        $('#bs-model-session-class .modal-body').load('<?php echo Yii::$app->urlManager->createUrl('/course/class-session/index') ?>',{class_id:class_id},
            function(data){
                $('#bs-model-session-class .modal-title').html(class_name);
                $('#bs-model-session-class').modal('show');
        });
    }
</script>