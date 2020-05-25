<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

$list_status = app\models\ListSetup::getItemByList('status');
$list_change = app\models\ListSetup::getItemByList('status_pay');

/* @var $this yii\web\View */
/* @var $model app\modules\course\models\Course */

$this->title = $model->course_name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Courses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="course-view">
    <div id="members-index" class="parkclub-wrapper parkclub-wrapper-search ">
        <div class="parkclub-rectangle parkclub-shadow">
           <div class="parkclub-invoice">
                <div class="parkclub-newm-left-title">
                    <div class="parkclub-header-left">
                        <a href="<?php echo Yii::$app->urlManager->createUrl('/course/default/index?tab=1'); ?>"<i class="glyphicon glyphicon-circle-arrow-left"></i></a>
                        <?php echo $this->title; ?>
                    </div>
                </div>
               <div class="parkclub-newm ">
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'course_name',
                            'course_content:ntext',
                            [
                                'attribute'=>'course_change',
                                'value' => Yii::t('app',$list_change[$model->course_change]),
                                'format' => 'raw'
                            ], 
                            [
                                'attribute'=>'course_amount',
                                'value' =>\app\models\ListSetup::getDisplayPrice($model->course_amount).\app\models\ListSetup::getCurrency(),
                                'format' => 'raw'
                            ],  
                            [
                                'attribute'=>'course_status',
                                'value' => Yii::t('app',$list_status[$model->course_status]),
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
                            <?php echo Yii::t('app', 'Class'); ?>
                        </div>
                        <div class="parkclub-header-right"><a class="btn btn-primary" href="<?php echo Yii::$app->urlManager->createUrl('/course/classc/create?course_id='.$model->course_id); ?>"><?php echo Yii::t('app', 'NEW CLASS'); ?></a></div>
                    </div>
                   <div class="parkclub-newm ">
                       <div id="view-class">
                           
                       </div>
                   </div>
                   <br>
               </div>
            </div>
    </div>
</div>
<!-- MODAL LIST SESSION CLASS-->
    <div id="bs-model-session-class" data-backdrop="static" data-keyboard="false" class="modal fade bs-example-modal-lg popupCheckin" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
        <div class="modal-dialog modal-lg" role="document" style="width: 60%; margin-top: 50px;">
            <div  class="modal-content">
                <div class="modal-header" style="background-color: rgb(50, 205, 139); color:#fff;">
                    <button class="close" type="button" data-dismiss="modal" aria-hidden="true">×</button>
                    <div class="fa fa-2x fa-calendar-plus-o" style="float:left;margin-right:10px"></div>
                    <div style="text-align: center">
                        <h4 class="modal-title"></h4>
                    </div>
               </div>
                <div class="modal-body">
                </div>
            </div>
        </div>
    </div>
<!-- END MODAL LIST SESSION CLASS -->
<script type="text/javascript">
    $( document ).ready(function() {
        loadClass();
    })
    function loadClass(){
        $('#view-class').load('<?php echo Yii::$app->urlManager->createUrl('/course/classc/index'); ?>',{'course_id':'<?php echo $model->course_id; ?>'});
    }
    
</script>