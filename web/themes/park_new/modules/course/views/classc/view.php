<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\course\models\Classc */

$this->title = $model->class_name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Classcs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="classc-view">
    
    <div id="members-index" class="parkclub-wrapper parkclub-wrapper-search ">
        <div class="parkclub-rectangle parkclub-shadow">
           <div class="parkclub-invoice">
                <div class="parkclub-newm-left-title">
                    <div class="parkclub-header-left">
                        <a href="<?php echo Yii::$app->urlManager->createUrl('/course/default/view?id='.$model->course_id); ?>"<i class="glyphicon glyphicon-circle-arrow-left"></i></a>
                        <?php echo $this->title; ?>
                    </div>
                </div>
               <div class="parkclub-newm ">
                    <?= DetailView::widget([
                         'model' => $model,
                         'attributes' => [
                            [
                                'attribute'=>'course_id',
                                'value' => $model->getCourseName(),
                                'format' => 'raw'
                            ], 
                             'class_content:ntext',
                            [
                                'attribute'=>'teacher_id',
                                'value' => $model->getTeacher(),
                                'format' => 'raw'
                            ],  
                            [
                                'attribute'=>'class_start_date',
                                'value' => \app\models\ListSetup::getDisplayDate($model->class_start_date),
                                'format' => 'raw'
                            ], 
                            [
                                'attribute'=>'class_schedule',
                                'value' =>$model->getSchedule('string').'<br>'
                                    .\app\models\ListSetup::getDisplayTime($model->class_start_time)." - ".
                                    \app\models\ListSetup::getDisplayTime($model->class_end_time),
                                'format' => 'raw'
                            ],  
 
                         ],
                     ]) ?>
               </div>
               <div class="parkclub-footer"></div>
           </div>
        </div>
    </div>
    
    <div id="members-index" class="parkclub-wrapper parkclub-wrapper-search ">
        <div class="parkclub-rectangle parkclub-shadow">
            <div class="parkclub-invoice">
                <div class="parkclub-newm-left-title">
                     <div class="parkclub-header-left">
                         <?php echo Yii::t('app', 'Member'); ?>
                     </div>
                     <div class="parkclub-header-right"></div>
                 </div>
                <div class="parkclub-newm ">
                    <div id="view-member">

                    </div>
                </div>
                <br>
            </div>
        </div>
    </div>

    <div id="members-index" class="parkclub-wrapper parkclub-wrapper-search ">
            <div class="parkclub-rectangle parkclub-shadow">
               <div class="parkclub-invoice">
                    <div class="parkclub-newm-left-title">
                        <div class="parkclub-header-left">
                            <?php echo Yii::t('app', 'Session'); ?>
                        </div>
                        <div class="parkclub-header-right"><a class="btn btn-primary" href="<?php echo Yii::$app->urlManager->createUrl('/course/class-session/create?class_id='.$model->class_id); ?>"><?php echo Yii::t('app', 'NEW SESSION'); ?></a></div>
                    </div>
                   <div class="parkclub-newm ">
                       <div id="view-session">
                           
                       </div>
                   </div>
                   <br>
               </div>
            </div>
    </div>

</div>
<script type="text/javascript">
    $( document ).ready(function() {
        $('#view-session').load('<?php echo Yii::$app->urlManager->createUrl('/course/class-session/index') ?>',{class_id:'<?php echo $model->class_id; ?>'});
        $('#view-member').load('<?php echo Yii::$app->urlManager->createUrl('/checkin_entity/default/index') ?>',{entity_id:'<?php echo $model->class_id; ?>',entity_type:'class'});
    })
    
</script>