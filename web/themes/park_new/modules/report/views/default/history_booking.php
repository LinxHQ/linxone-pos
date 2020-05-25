<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\modules\members\models\Members;
use app\modules\facility\models\Facility;
use app\modules\booking\models\Booking;
use app\modules\invoice\models\Payment;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use app\models\ListSetup;

$ListSetup = new ListSetup();
$this->title = Yii::t('app', 'Report');
$member = new Members();
// $dropdow_member = array(""=>Yii::t('app','All'))+$member->getDataDropdown();

$facility = new Facility();
$dropdow_facility = array(""=>Yii::t('app','All'))+$facility->getDataDropdown();

$member_selected = isset($_GET['member'])?$_GET['member']:'';
$facility_selected = isset($_GET['facility'])?$_GET['facility']:0;
$selected_start_date = (isset($_GET['start_date'])) ? $_GET['start_date'] : $start_date;
$selected_end_date = (isset($_GET['end_date'])) ? $_GET['end_date'] : $end_date;

$year_now = date('Y');
$month_now = date('m');
$day_now = date('d');
$house_now = 0;
$mitu_now = 0;
$second_now = 0;
if(isset($_GET['month']) && isset($_GET['year'])){
    $month = $_GET['month']+1;
    $year = $_GET['year'];
    $date_chart = mktime($house_now, $mitu_now, $second_now, $month,01, $year);
        $start_date = date('d/m/Y', $date_chart); 
     
        $end_date = date('t/m/Y', $date_chart); 
        
}
$a = false;
if(isset($_GET['a'])){
    $a=$_GET['a'];
    echo "<input id='check' value =".$a." style= 'display:none;' </input>";
}
if(isset($_GET['facility_name'])){
    $facility_name= $_GET['facility_name'];
    $facility = Facility::find()->where(['facility_name'=>$facility_name])->one();
    if($facility){
        $facility_selected = $facility->facility_id;
    }
}
?>
<div id="members-index" class="parkclub-wrapper parkclub-wrapper-search ">
            <div class="parkclub-rectangle parkclub-shadow">
                <div class="parkclub-rectangle-content">
                   <div class="parkclub-newm-left-title" >
                        <div class="parkclub-header-left" style = "color: rgb(50, 205, 139);">
                            <?php echo Yii::t('app', 'History Booking');?>
                        </div>
                    </div>


<?php 

  echo '<table>';
    echo '<tr>';
   

    echo '<td>';
    echo '<label class="control-label">'.Yii::t('app', 'From').': </label></td><td>';
    echo DatePicker::widget([
        'name' => 'dp_3',
        'type' => DatePicker::TYPE_COMPONENT_APPEND,
        'id'=>'start_date',
        'value' => $selected_start_date,
        'pluginOptions' => [
            'autoclose'=>true,
            'format' => 'dd/mm/yyyy'
        ]
    ]);
    echo '</td>';
    
    echo '<td style="padding:20px;">';
    echo '<label class="control-label">'.Yii::t('app', 'To').': </label></td><td>';
    echo DatePicker::widget([
        'name' => 'dp_3',
        'type' => DatePicker::TYPE_COMPONENT_APPEND,
        'id'=>'end_date',
        'value' => $selected_end_date,
        'pluginOptions' => [
            'autoclose'=>true,
            'format' => 'dd/mm/yyyy'
        ]
    ]);
    echo '</td>';
    
    echo '</tr>';

    echo '<tr>';

    echo '<td><label class="control-label">'.Yii::t('app', 'Member/PT').':</label></td>';
    echo '<td>';
     // echo Select2::widget([
        // 'name' => 'id223',
        // 'data' => $dropdow_member,
        // 'value' => $member_selected,
        // 'options' => [
   // //         'placeholder' => Yii::t('app', 'Select Member/PT ...'),
            // 'width'=>'100px',
            // 'id'=>'member'
  // //        'multiple' => true
        // ],
    // ]);
	echo '<input type="text" name="member" id="member" placeholder="'.Yii::t('app','Name').'" value="'.$member_selected.'" />';        
    echo '</td>';

    echo '<td style="padding:20px;"><label class="control-label">'.Yii::t('app', 'Facility').':</label></td>';
    echo '<td>';
    echo Select2::widget([
        'name' => 'id133',
        'data' => $dropdow_facility,
        'value' => $facility_selected,
        'options' => [
  //          'placeholder' => Yii::t('app', 'Select Facility ...'),
            'width'=>'100px',
            'id'=>'facility1'
  //        'multiple' => true
        ],
    ]);
    echo '</td>';

    echo '<td>';
    echo '<button style="margin-left:4px;background-color: rgb(50, 205, 139);" onclick="search_booking();return false;" class="btn btn-success">'.Yii::t('app', 'Search').'</button>';
    echo '</td>';

    echo '</tr>';
    echo '</table>';
    
$payment_Manage = new \app\modules\invoice\models\Payment();
$invoice = new \app\modules\invoice\models\invoice();
$ListSetup  = new app\models\ListSetup();

?>
<?=GridView::widget([
        'dataProvider' => $dataProvider,
//        'layout'=>"{items}",
//        'showFooter'=>TRUE,
        'footerRowOptions'=>[
            'style'=>'font-weight:bold;'
            ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn', 'header'=>Yii::t('app', 'No.')],
            [
	            'attribute' => 'member_id',
	            'format' => 'html',
	            'header' => Yii::t('app', 'Member name'),
	            'value' => function($model) {
                        $member = new Members();
                        return "<a href='".Yii::$app->urlManager->createUrl('members/default/update?id='.$model->member_id)."'>".$member->getMemberFullName($model->member_id)."</a>";
	            },
                   
	    ],
            [
	            'attribute' => 'facility_id',
	            'format' => 'html',
	            'header' => Yii::t('app', 'Facility name'),
	            'value' => function($model) {
                        $facility = Facility::findOne($model->facility_id);
                        if($facility)
                            return $facility->facility_name;
                        return "";
	            },
                   
	    ],
            [
	            'attribute' => 'book_id',
	            'format' => 'html',
	            'header' => Yii::t('app', 'Book date'),
	            'value' => function($model) {
                        $booking= Booking::findOne($model->book_id);
                        if($booking)
                            return $booking->book_date;
                        return "";
	            },
                   
	    ],
            [
	            'attribute' => 'book_id',
	            'format' => 'html',
	            'header' => Yii::t('app', 'Start time'),
	            'value' => function($model) {
                        $booking= Booking::findOne($model->book_id);
                        if($booking)
                            return $booking->book_startdate;
                        return "";
	            },
                   
	    ],
                            [
	            'attribute' => 'book_id',
	            'format' => 'html',
	            'header' => Yii::t('app', 'End time'),
	            'value' => function($model) {
                        $booking= Booking::findOne($model->book_id);
                        if($booking)
                            return $booking->book_enddate;
                        return "";
	            },
                   
	    ],
            [
                'header'=>Yii::t('app','Payment'),
                'format'=>'raw',
                'value'=>function($model){
                    $invoiceManage=new app\modules\invoice\models\invoice();
                    $invoice = app\modules\invoice\models\invoice::find()->where(['invoice_type_id'=>$model->book_id,'invoice_type'=>'booking'])->one();
                    if($invoice)
                        return Yii::t('app', $invoiceManage->getStatusInvoice($invoice->invoice_id));
                }
            ],
            [
                'header'=>Yii::t('app', 'Actions'),
                'format'=>'raw',
                'value'=>function($model){
                    $DefinePermission = new app\modules\permission\models\DefinePermission();
                    $m = 'booking';
                    $canCancelBooking = $DefinePermission->checkFunction($m, 'Cancel booking');
                    if(!$canCancelBooking)
                        return Yii::t('app', $model->getDropdownStatusBooking()[$model->book_status]);
                    $status = 1;
                    if($model->book_status==1)
                        $status = 0;
                    return Yii::t('app', $model->getDropdownStatusBooking()[$model->book_status]);
                }
            ],
        ],
    ]);                  

echo '<div class="parkclub-footer" style="text-align: center">';
    echo '<a href='.Yii::$app->urlManager->createUrl('/report/default/history_booking_pdf?start_date='.$start_date.'&end_date='.$end_date.'&member='.urlencode($member_selected).'&facility='.$facility_selected).' ><button class="btn btn-success" style = "background-color: rgb(50, 205, 139);">'.Yii::t('app', 'Print Pdf').'</button> </a>';
    echo '<a href='.Yii::$app->urlManager->createUrl('/report/default/history_booking_excel?start_date='.$start_date.'&end_date='.$end_date.'&member='.urlencode($member_selected).'&facility='.$facility_selected).' ><button class="btn btn-success" style = "background-color: rgb(50, 205, 139);">'.Yii::t('app', 'Export excel').'</button> </a>';
    echo '</div>';
    ?>
                </div>
            </div>
</div>
<div class="parkclub-wrapper parkclub-wrapper-search ">
            <div class="parkclub-rectangle parkclub-shadow">
                <div class="parkclub-rectangle-content" >
                   <div class="parkclub-newm-left-title" >
                        <div class="parkclub-header-left" style = "color: rgb(50, 205, 139);">
                            <?php echo Yii::t('app', 'Booking Chart');?>
                        </div>
                       <div class="parkclub-header-right">
                           <?php echo Yii::t('app', 'Facility');?><?php echo yii\bootstrap\Html::dropDownList('change-facility',"",array('0'=>Yii::t('app','All'))+$dropdow_facility,['onchange'=>'load_chart_booking();return false','style'=>'width:30%','id'=>'facility-booking']); ?>
                           <?php echo Yii::t('app', 'Year');?><?php echo yii\bootstrap\Html::dropDownList('change-year',date('Y'),$ListSetup->year(),['onchange'=>'load_chart_booking();return false;','style'=>'width:30%','id'=>'year-booking']); ?>
                       </div>
                    </div>
                    <br>
                    <div id="live-chart">

                    </div>
                </div>
            </div>
</div>
<div class="parkclub-wrapper parkclub-wrapper-search ">
            <div class="parkclub-rectangle parkclub-shadow">
                <div class="parkclub-rectangle-content" >
                   <div class="parkclub-newm-left-title" >
                        <div class="parkclub-header-left" style = "color: rgb(50, 205, 139);">
                            <?php echo Yii::t('app', 'Facility booking Chart');?>
                        </div>
                       <div class="parkclub-header-right">
                           <?php echo Yii::t('app', 'Year');?><?php echo yii\bootstrap\Html::dropDownList('change-year',date('Y'),$ListSetup->year(),['onchange'=>'load_chart_year(this.value)','style'=>'width:30%']); ?>
                       </div>
                    </div>
                    <br>
                    <div id="live-facility-chart">

                    </div>
                </div>
            </div>
</div>
<script>    
    $( document ).ready(function() {
        load_chart_booking();
        load_chart_year('<?php echo date('Y'); ?>');
        var a = $('#check').val();
        if(a==1){
            search_booking();
        }
    })
  //  search_booking();
    function search_booking()
    {
        var member=$('#member').val();
        var facility = $('#facility1').val();
        var start_date=$('#start_date').val();
        var end_date=$('#end_date').val();
        
      window.location.href='history_booking?member='+member+'&start_date='+start_date+'&end_date='+end_date+'&facility='+facility;
    }

    function load_chart_booking(){
        $.blockUI();
        var facility= $('#facility-booking').val();
        var year = $('#year-booking').val();
        $('#live-chart').load('<?php echo Yii::$app->urlManager->createUrl('report/default/chart-booking') ?>',{facility:facility,year:year},function(){
            $.unblockUI();
        });
    }
    function load_chart_year(year){
        $.blockUI();
        $('#live-facility-chart').load('<?php echo Yii::$app->urlManager->createUrl('report/default/chart-facility') ?>',{year:year},function(){
            $.unblockUI();
        });
    }
</script>