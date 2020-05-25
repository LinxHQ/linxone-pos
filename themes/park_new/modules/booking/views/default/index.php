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
/* @var $this yii\web\View */
/* @var $searchModel app\models\BookingSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

//Check permission 
$m = 'booking';
$BasicPermission = new \app\modules\permission\models\BasicPermission();
$canList = $BasicPermission->checkModules($m, 'list');
if(!$canList){
    echo Yii::t('app', "You don't have permission with this action.");
    return ;
}
//End check permission

//Check permision PT booking
$module_pt_booking='trainer_booking';
$canListPtBooking = $BasicPermission->checkModules($module_pt_booking, 'list');

$this->title = Yii::t('app', 'Bookings');
$getFacitity = \app\modules\facility\models\Facility::find()->all();
$allBooking = app\modules\booking\models\Booking::find()->all();
$facility = new app\modules\facility\models\Facility();
$facility_id=isset($_GET['facility_id'])>0?$_GET['facility_id']:0;
$date=isset($_GET['date'])!=""?$_GET['date']:date('Y-m-d');
 
$model = new \app\modules\booking\models\Booking();
$model->facility_id=$facility_id;
$model->book_date=$date;
$active_report = isset($_GET['key'])?true:false;
$active_booking = (!isset($_GET['key']))?true:false;

?>
<div class="parkclub-subtop-bar">
    <div class="parkclub-nameplate"><div class="parkclub-iconbg"><img src="<?php echo Yii::$app->urlManager->baseUrl; ?>/image/park_new/bookmark.png" alt=""></div> <h3><?php echo Yii::t('app', 'Bookings'); ?></h3></div>
    <div class="parkclub-search">
      
    </div>
</div>
<div id="members-index" class="parkclub-wrapper parkclub-wrapper-search ">
    <div class="parkclub-rectangle parkclub-shadow">
        <br>
       <div class="parkclub-invoice">
<!--            <div class="booking-index">-->
                <?php 
                    $tab=0;
                    $active_report=false;
                    $active_pt_booking=false;
                    $active_booking=false;
                    if(isset($_GET['tab']))
                        $tab = $_GET['tab'];
                    if($tab==1)
                        $active_report=true;
                    else if($tab==2)
                        $active_pt_booking=true;
                    else if($tab==0)
                        $active_booking=true;

                    $tab_pt_booking=['label'=>false];
                    if($canListPtBooking)
                        $tab_pt_booking=[
                        'label'=>'<i class="glyphicon glyphicon-user"></i> '.Yii::t('app', 'PT Booking'),
                        'content'=>$this->renderAjax('@app/modules/trainer_booking/views/default/_index_booking_calendar.php'),
                            'linkOptions' => array('onclick'=>'active_pt_booking();'),'active'=>$active_pt_booking,
                            ];
                    $items = [
                    [
                        'label'=>'<i class="glyphicon glyphicon-home"></i> '.Yii::t('app', 'Facility Booking'),
                        'content'=>$this->renderAjax('_index_booking_calendar'),
                        'active'=>$active_booking,
                        'linkOptions' => array('onclick'=>'active_booking();')
            //            'linkOptions'=>['data-url'=>\yii\helpers\Url::to(['/booking/default/bookingcalendar?test=1'])]
                    ],
                    [
                        'label'=>'<i class="glyphicon glyphicon-user"></i> '.Yii::t('app', 'Facility Report'),
                        'content'=>$this->renderAjax('_index_booking_report',['dataProvider'=>$dataProvider]),
                        'active'=>$active_report,
                        'linkOptions' => array('onclick'=>'active_report();')
            //            'linkOptions'=>['data-url'=>\yii\helpers\Url::to(['/booking/default/bookingcalendar?facility_id=0&date=2016-12-08'])]
                    ],

                    $tab_pt_booking
                ];

                echo TabsX::widget([
                    'items'=>$items,
                    'position'=>TabsX::POS_ABOVE,
                    'encodeLabels'=>false
                ]);

                ?>
<!--            </div>-->
       </div>
    </div>
</div>
<script src="<?php echo Yii::$app->request->baseUrl; ?>/js/bootstrap.js" type="text/javascript" ></script>
<script type="text/javascript">
    (function($) {
        $('#keysearch').keypress(function(event){
            var keycode = (event.keyCode ? event.keyCode : event.which);
            if (keycode == '13') {
              searchBook();
            }
        });
    })(jQuery);
    function searchBook(){
        var keySearch = $('#keysearch').val();
//       if(keySearch==""){
//            alert("Please, enter keyword.");
//            return false;
//        }
        $('#report_booking').load('bookingreport?key='+keySearch);
//        location.href = 'index?key='+keySearch;
        
    }
    
    function active_pt_booking()
    {
        var url='<?php echo Yii::$app->urlManager->createUrl('/booking/default/index?tab=2');?>';
        window.location.href=url;
    }
    function active_booking()
    {
        var url='<?php echo Yii::$app->urlManager->createUrl('/booking/default/index?tab=0');?>';
        window.location.href=url;
    }
    function active_report()
    {
        var url='<?php echo Yii::$app->urlManager->createUrl('/booking/default/index?tab=1');?>';
        window.location.href=url;
    }
    function updateStatus(book_id,status_id){
        $.ajax({
            'type':'POST',
            'url':'<?php echo YII::$app->urlManager->createUrl('/booking/default/updatestatus'); ?>',
            'data':{book_id:book_id,status_id:status_id},
            'success':function(data){
                data = jQuery.parseJSON(data);
                if(data.status == 'success')
                    alert('<?php echo Yii::t('app','Successfully update'); ?>');
                else if(data.status=='exit'){
                    alert(data.msg);
                    location.reload();
                }
                else
                    alert('update error');
            }
        });
    }
    
    function loadCalendarByFacility()
    {
        var facitily = $('#facility_search').val();
        var date = $('#search_book_date').val();
        location.href = 'index?facility_id='+facitily+'&date='+date;
    }
    function loadCalendarByTrainer(tab)
    {
        var trainer_id = $('#trainer_search').val();
        var date = $('#search_book_date_t').val();
        location.href = 'index?tab='+tab+'&trainer_id='+trainer_id+'&date='+date;
    }
</script>
