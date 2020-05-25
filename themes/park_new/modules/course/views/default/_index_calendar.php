<?php
use yii\helpers\Html;
use yii\grid\GridView;

use yii\helpers\Url;
use yii\bootstrap\Modal;
use yii\web\JsExpression;
use ptrnov\fullcalendar\FullcalendarScheduler;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
$class_id = "";
$date = date('Y-m-d');
if(isset($_GET['date']))
{
    $date = $_GET['date'];
}
?>
<?=FullcalendarScheduler::widget([
    'modalSelect'=>[

        'id' => 'modal-select',                                         
        'id_content'=>'modalContent',                                   
        'headerLabel' => Yii::t('app', 'New Session'),                          
        'modal-size'=>'modal-lg',     

    ],
            'options'=>[
                'id'=> 'calendar_test',                                   
                'language'=>'en',
            ],
            'header'        => [
                'left'   => 'today prev,next',
                'center' => 'title',
                'right'  => 'timelineDay,timelineWeek,timelineMonth',
            ],
    'optionsEventAdd'=>[
        'events' => Url::to(['/course/default/eventcalendar']),        
        'resources'=> '#',
        //disable 'eventDrop' => new JsExpression($JSDropEvent),
        'eventDropUrl'=>Yii::$app->urlManager->createUrl('/course/default/drop-calendar'),                             //should be set "your Controller link" to get(start,end) from select. You can use model for scenario.
        'eventSelectUrl'=>Yii::$app->urlManager->createUrl('/course/class-session/create-calendar'),
        'eventResize'=>new JsExpression(
                "function(event, delta, revertFunc) {
                    var child = event.parent;
                    var status = event.status;

                    var dateTime2 = new Date(event.end);
                    var dateTime1 = new Date(event.start);
                    var tgl1 = moment(dateTime1).format('YYYY-MM-DD');
                    var tgl2 = moment(dateTime2).subtract(1, 'days').format('YYYY-MM-DD');
                    var my_start_time = moment(event.start).format('HH:mm');
                    var my_end_time = moment(event.end).format('HH:mm');
                    var id = event.id;
                    if(child != 0 && status != 1){
                            $.get('".Yii::$app->urlManager->createUrl('/course/default/drop-calendar')."',{'id':id,'start':tgl1,'end':tgl2,'start_time':my_start_time,'end_time':my_end_time});
                    }

                }"
                )

        //should be set "your Controller link" to get(start,end) from select. You can use model for scenario            
    ], 
    'clientOptions' => [
                    'now'                => $date,
                    'editable'           => true, // enable draggable events
                    'resizable'=>true,
                    'aspectRatio'        => 1.8,
                    'scrollTime'         => '00:00', // undo default 6am scrollTime
                    'minTime'=> '06:00:00',//'timelineDay',//agendaDay',
                    'maxTime'=> '22:00',//'timelineDay',//agendaDay',
                    'theme'=> true,
                    'selectable' => true,
                    'header'             => [
                            'left'   => 'today prev,next',
                            'center' => 'title',
                            'right'  => 'timelineDay,timelineThreeDays,agendaWeek,month',
                    ],
                    'defaultView'        => 'timelineDay',
                    'views'              => [
                            'timelineThreeDays' => [
                                    'type'     => 'timeline',
                                    'duration' => ['days' => 3],
                            ],
                    ],
                    'resourceLabelText' => Yii::t('app','Class'),
                    'resourceGroupField' => 'building',
                    'resources'=>Url::to(['/course/default/schedule?class_id='.$class_id]),
                    'events'=>Url::to(['/course/default/eventcalendar']),

                    'eventSelectUrl'=>Yii::$app->urlManager->createUrl('/booking/default/create'),
        
                                       // don't set if used "modalSelect"
                    'eventClick' => new JsExpression("function(event, element, view){
                        
                                            var id = event.id;
                                            popcheckin(id,event.resourceId);
                                    }
                    "),
        
                'modalSelect'=>[
                     /**
                      * modalSelect for cell Select
                      * 'clientOptions' => ['selectable' => true]                    //makseure set true.
                      * 'clientOptions' => ['select' => function or JsExpression]    //makseure disable/empty. if set it, used JsExpressio to callback.          
                      * @author piter novian [ptr.nov@gmail.com]                     //"https://github.com/ptrnov/yii2-fullcalendar".
                     */
                     'id' => 'modal-select',                                         //set it, if used FullcalendarScheduler more the one on page.
                     'id_content'=>'modalContent',                                   //set it, if used FullcalendarScheduler more the one on page.
                     'headerLabel' => 'Model Header Label',                          //your modal title,as your set. 
                     'modal-size'=>'modal-lg'                                        //size of modal (modal-xs,modal-sm,modal-sm,modal-lg).
                 ],
            ],

]); ?>
<!-- MODAL CHECKIN -->
<div id="bs-model-checkin-member" class="modal fade bs-example-modal-lg popupCheckin" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog modal-lg" role="document" style="width: 950px;">
        <div  class="modal-content">
            <div class="modal-header" style="background-color: rgb(50, 205, 139); color:#fff; text-align:center">
                <button class="close" type="button" data-dismiss="modal" aria-hidden="true">×</button>
           <div class="fa fa-2x fa-calendar-plus-o" style="float:left;margin-right:10px"></div>
           <div>
               <h4 class="modal-title"><?php Yii::t('app','Checkin Member');?></h4>
           </div>
           </div>
           <div class="modal-body" id="modal-content-checkin"></div>
        </div>
    </div>
</div>
<!-- END MODAL CHECKIN -->

<script type="text/javascript">
    function popcheckin(session_id,class_id){
        $('.modal-content').css({'min-height':'200px'});
        $('#modal-content-checkin').load('<?php echo Yii::$app->urlManager->createUrl('/checkin_entity/default/list-member-checkin'); ?>',{"session_id":session_id,entity_type:"class",entity_id:class_id},
            function(data){
                $('#bs-model-checkin-member').modal('show'); 
            });
    }
</script>
