<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\web\JsExpression;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\event\models\EventSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Events');
$this->params['breadcrumbs'][] = $this->title;
?>

    
     <?= \yii2fullcalendar\yii2fullcalendar::widget(array(
        'events'=> $events,
        'eventDrop'=>new JsExpression(
                "function(event, delta, revertFunc) {
                    var child = event.parent;
                    var status = event.status;

                    var dateTime2 = new Date(event.end);
                    var dateTime1 = new Date(event.start);
                    var tgl1 = moment(dateTime1).format('YYYY-MM-DD');
                    var tgl2 = moment(dateTime2).format('YYYY-MM-DD');
                    var my_start_time = moment(event.start).format('HH:mm');
                    var my_end_time = moment(event.end).format('HH:mm');
                    var id = event.id;
                    if (confirm('Are you sure about this change?')){
                        $.get('".Yii::$app->urlManager->createUrl('/event/default/drop-calendar')."',{'id':id,'start':tgl1,'end':tgl2,'start_time':my_start_time,'end_time':my_end_time});
                    }
                   

                }"
        ),
         
        'eventResize'=>new JsExpression(
                "function(event, delta, revertFunc) {
                    var child = event.parent;
                    var status = event.status;

                    var dateTime2 = new Date(event.end);
                    var dateTime1 = new Date(event.start);
                    var tgl1 = moment(dateTime1).format('YYYY-MM-DD');
                    var tgl2 = moment(dateTime2).format('YYYY-MM-DD');
                    var my_start_time = moment(event.start).format('HH:mm');
                    var my_end_time = moment(event.end).format('HH:mm');
                    var id = event.id;
                    if(child != 0 && status != 1){
                        if (confirm('Are you sure about this change?')){
                            $.get('".Yii::$app->urlManager->createUrl('/event/default/drop-calendar')."',{'id':id,'start':tgl1,'end':tgl2,'start_time':my_start_time,'end_time':my_end_time});
                        }
                    }

                }"
        ),
         
        'clientOptions' => [
            'selectable' => true,
            'selectHelper' => true,
            'droppable' => true,
            'draggable' => true,
            'editable' => true,
            'eventClick' => new JsExpression("function(event, element, view){
                                            var id = event.id;
                                            popcheckin(id);
                                    }
                    "),
        ],
         ));
    ?>

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
    function popcheckin(event_id){
        $('.modal-content').css({'min-height':'200px'});
        $('#modal-content-checkin').load('<?php echo Yii::$app->urlManager->createUrl('/checkin_entity/default/list-member-checkin'); ?>',{entity_type:"event",entity_id:event_id},
            function(data){
                $('#bs-model-checkin-member').modal('show'); 
            });
    }
</script>