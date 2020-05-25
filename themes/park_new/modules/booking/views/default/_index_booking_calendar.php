<?php
use yii\helpers\Html;
use yii\grid\GridView;

use yii\helpers\Url;
use yii\bootstrap\Modal;
use yii\web\JsExpression;
use ptrnov\fullcalendar\FullcalendarScheduler;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use kartik\tabs\TabsX;

$this->title = Yii::t('app', 'Bookings');
$getFacitity = \app\modules\facility\models\Facility::find()->all();
$allBooking = app\modules\booking\models\Booking::find()->all();
$facility = new app\modules\facility\models\Facility();
$facility_id=isset($_GET['facility_id']) ? $_GET['facility_id'] : 0;
$date=isset($_GET['date'])!=""?$_GET['date']:date('Y-m-d');
 
$model = new \app\modules\booking\models\Booking();
$model->facility_id=$facility_id;
$model->book_date=$date;

//$test = $model->getTimeHaveBook(4,false,'2017-08-30');
//print_r($test);
//$test1 = $model->getStartTime(4, false, '2017-08-30');
////echo $test1;
//print_r($test1);
//exit();

$form = ActiveForm::begin(); ?>
<div class="book-facility-search">
    <div class="col-xs-6">
        <div class="col-xs-2" style="padding-top: 10px;text-align: right">
            <span class="book-label"><?php echo Yii::t('app','Facility');?>:</span>
        </div>
        <div class="col-xs-10">
            <?php echo yii\bootstrap\Html::dropDownList('facility_search', $facility_id, array(Yii::t('app', 'All')) + $facility->getDataDropdown(),['onchange'=>'loadCalendarByFacility();','id'=>'facility_search']) ?>
        </div>
    </div>
    <div class="col-xs-6">
        <div class="col-xs-2" style="padding-top: 8px;text-align: right">
            <span class="book-label"><?php echo Yii::t('app','Date');?>:</span>
        </div>
        <div class="col-xs-10">
            <?php                     
                echo DatePicker::widget([
                    'name' => 'dp_3',
                    'type' => DatePicker::TYPE_COMPONENT_APPEND,
                    'id'=>'search_book_date',
                    'value' => $date,
                    'pluginOptions' => [
                        'autoclose'=>true,
                        'format' => 'yyyy-mm-dd',
                    ],
                    'pluginEvents' => [
                        'change' => 'function() { loadCalendarByFacility(); }',
                        ],
                ]);   
            ?>
        </div>
    </div>
</div>
    <?php ActiveForm::end(); ?>
    <div>
        <?php
        $date_facility = date('Y-m-d');
        if(isset($_GET['date']))
        {
            $date_facility = $_GET['date'];
        }
        
        ?>
        <?=FullcalendarScheduler::widget([  
                    
            'modalSelect'=>[
               
                'id' => 'modal-select',                                         
                'id_content'=>'modalContent',                                   
                'headerLabel' => Yii::t('app', 'New Booking'),                          
                'modal-size'=>'modal-lg',     
                
            ],
            'header'        => [
                'left'   => 'today prev,next',
                'center' => 'title',
                'right'  => '',
            ],
            'options'=>[
                'id'=> 'calendar_test',                                   
                'language'=>'en',
            ],
            'optionsEventAdd'=>[
                'events' => Url::to(['/booking/default/eventcalendar']),        
                'resources'=> '#',        
                //disable 'eventDrop' => new JsExpression($JSDropEvent),
                'eventDropUrl'=>'/test/drop-child',                             //should be set "your Controller link" to get(start,end) from select. You can use model for scenario.
                'eventSelectUrl'=>Yii::$app->urlManager->createUrl('/booking/default/create'),  
                
                //should be set "your Controller link" to get(start,end) from select. You can use model for scenario            
            ],     
            
            'clientOptions' => [
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
                'titleFormat' => 'D-M-Y',
                'language'=>'en',
                'selectable' => true,
                'selectHelper' => true,
                'droppable' => true,
                'editable' => true,
                                       // don't set if used "modalSelect"
                'eventClick' => new JsExpression("function(event, element, view){
					var child = event.parent;
					var status = event.status;

					var dateTime2 = new Date(event.end);
					var dateTime1 = new Date(event.start);
					var tgl1 = moment(dateTime1).format('YYYY-MM-DD');
					var tgl2 = moment(dateTime2).subtract(1, 'days').format('YYYY-MM-DD');

					var id = event.id;
//                                        var url = '".Yii::$app->urlManager->createUrl('/booking/default/update/')."?id='+id;
//                                         
//                                        window.open(url);
                                        memberPopcheckin(id);
                                        
                                        
                                       
				}
		"),
                       
                'eventSelectUrl'=>Yii::$app->urlManager->createUrl('/booking/default/create'),
                'now' => $date_facility,
                'firstDay' =>'0',
                'theme'=> true,
                'aspectRatio'=> 1.8,
                'timeFormat'=>"h:i",
                //'scrollTime'=> '00:00', // undo default 6am scrollTime
                'defaultView'=> 'timelineDay',//'timelineDay',//agendaDay',
                'minTime'=> '06:00:00',//'timelineDay',//agendaDay',
                'maxTime'=> '22:00',//'timelineDay',//agendaDay',
                
                'views'=> [
                    'timelineOneDays' => [
                        'type'     => 'timeline',
                        'duration' => [
                            'days' => 1,
                        ],
                    ], 

                ],              
                'resourceLabelText' => YII::t('app', 'Facilities'),

                'resources'=> Url::to(['/booking/default/schedule?facility_id='.$facility_id]),        //should be set "your Controller link" 
                'events' => Url::to(['/booking/default/eventcalendar']),             //should be set "your Controller link"  
                'eventDrop'=>new JsExpression("function(event, element, view){
					var child = event.parent;
					var status = event.status;

					var dateTime2 = new Date(event.end);
					var dateTime1 = new Date(event.start);
					var tgl1 = moment(dateTime1).format('YYYY-MM-DD');
					var tgl2 = moment(dateTime2).subtract(1, 'days').format('YYYY-MM-DD');

					var id = event.id;
                                        
					if(child != 0 && status != 1){
						
					}
				}
		"),
                
                
                
            ],  

        ]); ?>  
    
    
    </div>
<!-- MODAL CHECKIN -->
    <div id="bs-model-checkin-book-m" class="modal fade bs-example-modal-lg popupCheckin" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
        <div class="modal-dialog modal-lg" role="document" style="width: 1080px;">
            <div  class="modal-content">
                <div class="modal-header" style="background-color: rgb(50, 205, 139); color:#fff; text-align:center">
                    <button class="close" type="button" data-dismiss="modal" aria-hidden="true">×</button>
               <div class="fa fa-2x fa-calendar-plus-o" style="float:left;margin-right:10px"></div>
               <div>
                   <h4 class="modal-title"><?php echo yii::t('app','Checkin member book');?></h4>
               </div>
               </div>
               <div class="modal-body" id="modal-content-checkin-m"></div>
            </div>
        </div>
    </div>
<!-- END MODAL CHECKIN -->

<script type="text/javascript">
    $( document ).ready(function() {
        var tour_step = '<?php echo Yii::$app->session['tour-step']; ?>';
        if(tour_step=='<?php echo app\models\Config::TOUR_BOOKING; ?>')
        {
            tour_booking.restart();
            tour_booking.start();
        }
    })
    function memberPopcheckin(book_id){
        $('.modal-content').css({'min-height':'200px'});
        $('#modal-content-checkin-m').load('<?php echo Yii::$app->urlManager->createUrl('/booking/default/popup-member-checkin'); ?>',{"book_id":book_id},
            function(data){
                $('#bs-model-checkin-book-m').modal('show'); 
            });
    }
</script>