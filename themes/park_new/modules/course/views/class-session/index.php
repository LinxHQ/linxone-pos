<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\course\models\ClassSessionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Class Sessions');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="class-session-index">
<?php Pjax::begin([
    'id'=>'ajax-session-class'
]); ?>  
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
        'summary'=>false,
        'id'=>'gridview-session-class',
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute'=>'class_session_date',
                'format'=>'raw',
                'value'=>function($model){
                    return \app\models\ListSetup::getDisplayDate($model->class_session_date);
                }
            ], 
            [
                'attribute'=>'class_session_start_time',
                'format'=>'raw',
                'value'=>function($model){
                    return \app\models\ListSetup::getDisplayTime($model->class_session_start_time);
                }
            ],
            [
                'attribute'=>'class_session_end_time',
                'format'=>'raw',
                'value'=>function($model){
                    return \app\models\ListSetup::getDisplayTime($model->class_session_end_time);
                }
            ],
           
            'class_session_note',
            // 'class_session_status',

            ['class' => 'yii\grid\ActionColumn',
                'template'=>'{update}{delete}'],
        ],
    ]); ?>
<?php Pjax::end(); ?>
</div>
