<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\modules\members\models\Members;
use app\modules\checkin\models\MembersCheckin;
use app\modules\facility\models\Facility;
use app\modules\booking\models\Booking;
use app\modules\invoice\models\Payment;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use app\models\ListSetup;
use app\modules\training\models\MemberTrainers;
use app\modules\training\models\MemberTrainings;
use app\modules\invoice\models\InvoiceItem;
use app\modules\invoice\models\Invoice;
use app\modules\training\models\MemberTrainingsSearch;
// $revenue = new \app\modules\revenue_type\models\RevenueItem();
// $package_arr = $revenue->getRevenueItemByEntry(2,'array','index');
$trainer_selected = isset($_GET['trainer'])?$_GET['trainer']:'';

?>
<div id="members-index" class="parkclub-wrapper parkclub-wrapper-search ">
            <div class="parkclub-rectangle parkclub-shadow">
                <div class="parkclub-rectangle-content">
                   <div class="parkclub-newm-left-title" >
                        <div class="parkclub-header-left" style = "color: rgb(50, 205, 139);">
                             <?php echo Yii::t('app', 'PT SESSION SERVICE REPORT');?>
                        </div>
                    </div>


<?php 

  echo '<table>';
    echo '<tr>';
	echo '<td style="padding:20px;">';
	echo '<label class="control-label">'.Yii::t('app','Trainer').': </label></td><td style="width:200px;">';
	echo '<input type="text" name="trainer" id="trainer" placeholder="'.Yii::t('app','Name').'" value="'.$trainer_selected.'" />';        
	echo '</td>';

    echo '<td>';
    echo '<label class="control-label">'.Yii::t('app', 'From').': </label></td><td>';
    echo DatePicker::widget([
        'name' => 'dp_3',
        'type' => DatePicker::TYPE_COMPONENT_APPEND,
        'id'=>'start_date',
        'value' => $start_date,
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
        'value' => $end_date,
        'pluginOptions' => [
            'autoclose'=>true,
            'format' => 'dd/mm/yyyy'
        ]
    ]);
    echo '</td>';
    

    echo '<td>';
    echo '<button style="margin-left:4px; background-color: rgb(50, 205, 139);margin-bottom: 10px;" onclick="search_booking();return false;" class="btn btn-success">'.Yii::t('app', 'Search').'</button>';
    echo '</td>';

    echo '</tr>';
    echo '</table>';
    
$payment_Manage = new \app\modules\invoice\models\Payment();
$invoice = new \app\modules\invoice\models\invoice();
$ListSetup  = new app\models\ListSetup();

?>
<?=GridView::widget([
        'dataProvider' => $dataProvider,
        // 'layout'=>"{items}",
        // 'showFooter'=>FALSE,
        'footerRowOptions'=>[
            'style'=>'font-weight:bold;'
            ],
        // 'tableOptions' =>['class'=>'scroll-report', 'id'=>'pt-session'],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn', 'header'=>Yii::t('app','No.')],
            [
	            'attribute' => 'trainer_id',
	            'format' => 'html',
	            'header' => Yii::t('app','Trainer'),
	            'value' => function($model) {
                    $member = new Members();
					return $member->getMemberFullName($model->trainer_id);
	            },
                   
	    ],
            [
	            'attribute' => 'checkin_time',
	            'format' => 'html',
	            'header' => Yii::t('app','Checkin time'),
	            'value' => function($model) {
					return ($model->checkin_time != "0000-00-00 00:00:00" and $model->checkin_time !='') ? date('d/m/Y H:i:s',  strtotime($model->checkin_time)) : "";
	            },
                   
	    ],
            [
	            'attribute' => 'book_id',
	            'format' => 'html',
	            'header' => Yii::t('app','Booking code'),
	            'value' => function($model) {
                    $booking= Booking::findOne($model->booking_id);
                    if($booking)
						return $booking->confirmation_code;
					else return '';
	            },
                   
	    ],
		[
	            'attribute' => 'package_id',
	            'format' => 'html',
	            'header' => Yii::t('app','Package'),
	            'value' => function($model) {
                    $revenue = new app\modules\revenue_type\models\RevenueItem();
					$trainning_package = $revenue->getRevenueItemByEntry(2,'array','index');
					if($model->package_id and array_key_exists($model->package_id,$trainning_package))
						return $trainning_package[$model->package_id];
					else return '';
	            },
                   
	    ],
		[
	            'attribute' => 'trainer_id',
	            'format' => 'html',
	            'header' => Yii::t('app','Member name'),
	            'value' => function($model) {
                    $member_id = 0;
					if($model->booking_id){
						$booking = Booking::findOne($model->booking_id);
						if($booking) {
							$tranier_package = MemberTrainings::findOne($booking->traning_id);
							if($tranier_package)
								$member = Members::findOne($tranier_package->member_id);
							if($member)
								$member_id = $member->member_id;
						}	
					} else {
						if($model->checkin_id) {
							$checkin = MembersCheckin::findOne($model->checkin_id);
							if($checkin)
								$member_id = $checkin->member_id;
						}
					}
			
					$member = new Members();
					return ($member_id) ? "<a href='".Yii::$app->urlManager->createUrl('members/default/update?id='.$member_id)."'>".$member->getMemberFullName($member_id)."</a>" : '';
	            },
                   
	    ],
		[
	            'attribute' => 'book_id',
	            'format' => 'html',
	            'header' => Yii::t('app','Note'),
	            'value' => function($model) {
                    $booking= Booking::findOne($model->booking_id);
                    if($booking)
						return $booking->book_notes;
					else return '';
	            },
                   
	    ],
            // [
	            // 'attribute' => 'member_id',
	            // 'format' => 'html',
	            // 'header' => Yii::t('app','Member barcode'),
	            // 'value' => function($model) {
                        // $member = MemberTrainings::find()->where(["member_training_id"=>$model->traning_id])->all();
                        // $member_id= "";
                        // foreach ($member as $data) {
                                // $member_id.= $data->member_id.'';
                            // }
                            
                        // $member = Members::find()->where(["member_id"=>$member_id])->all();
                        // $member_barcode ="";
                        // foreach ($member as $data) {
                                // $member_barcode .= $data->member_barcode.'<br>';
                            // }
        
                        // return $member_barcode;
	            // },
                   
	    // ],
            // [
	            // 'attribute' => 'member_id',
	            // 'format' => 'html',
	            // 'header' => Yii::t('app','Member name'),
	            // 'value' => function($model) {
                        // $member = MemberTrainings::find()->where(["member_training_id"=>$model->traning_id])->all();
                        // $member_id= "";
                        // foreach ($member as $data) {
                                // $member_id.= $data->member_id.'';
                            // }
                            
                        // $member = Members::find()->where(["member_id"=>$member_id])->all();
                        // $member_name="";
                        // foreach ($member as $data) {
                                // $member_name .= $data->getMemberFullName($member_id).'<br>';
                            // }
        
                        // return $member_name;
	            // },
                   
	    // ],
// //            [
// //	            'attribute' => 'member_id',
// //	            'format' => 'html',
// //	            'header' => 'PT contract No.',
// ////	            'value' => function($model) {
// ////                        $member = Members::findOne($model->member_id);
// ////                        return $member->getMemberFullName($model->member_id);
// ////	            },
// ////                   
// //	    ],
// //            [
// //	            'attribute' => 'member_id',
// //	            'format' => 'html',
// //	            'header' => 'Type of contract',
// //	            'value' => function($model) {
// //                        $type ="PT";
// //                        return $type;
// //	            },                   
// //	    ],
            // [
	            // 'attribute' => 'member_id',
	            // 'format' => 'html',
	            // 'header' => Yii::t('app','Training type'),
	            // 'value' => function($model) {
                        // $member = MemberTrainings::find()->where(["member_training_id"=>$model->traning_id])->all();
                        // $training_type_id= "";
                        // foreach ($member as $data) {
                                // $training_type_id .= $data->package_id.'';
                            // }
                            // $listsetup = new app\models\ListSetup();
                        // if($training_type_id>0){
                            // $revenue = new \app\modules\revenue_type\models\RevenueItem();
                            // $package_arr = $revenue->getRevenueItemByEntry(2,'array','index');
                            // return $training_type = (isset($package_arr[$training_type_id]) ? $package_arr[$training_type_id] : "");              
                        // }else{
                            // return $training_type_id;
                        // }
	            // },                   
	    // ],
            // [
	            // 'attribute' => 'member_id',
	            // 'format' => 'html',
	            // 'header' => Yii::t('app','Total session'),
	            // 'value' => function($model) {
                        // $member = MemberTrainings::find()->where(["member_training_id"=>$model->traning_id])->all();
                    // //    $member = new MemberTrainings($model->member_id);
                        // $total = "";
                        // foreach ($member as $data) {
                                // $total .= $data->training_total_sessions.'<br>';
                            // }
                             // return $total;
                        
	            // },                   
	    // ],
            // [
	            // 'attribute' => 'invoice_id',
	            // 'format' => 'html',
	            // 'header' => Yii::t('app','Price'),
	            // 'value' => function($model) {
                        // $member = MemberTrainings::find()->where(["member_training_id"=>$model->traning_id])->all();
                        // $member_id= "";
                        // foreach ($member as $data) {
                                // $member_id.= $data->member_id.'';
                            // }
                        // $invoice = invoice::find()->where(["member_id"=>$member_id])->all();
                        // $invoice_id = "";
                        // foreach ($invoice as $data) {
                                // $invoice_id .= $data->invoice_id.'<br>';
                            // }
                        // $InvoiceItem = InvoiceItem::find()->where(["invoice_id"=>$invoice_id])->all();
                        // $price ="";
                        // foreach ($InvoiceItem as $data) {
                                // $price = $data->invoice_item_amount;
                            // }
                        
                       
                        // return number_format((float)$price,0,",",".");
	            // },                   
	    // ],
            // [
	            // 'attribute' => 'member_id',
	            // 'format' => 'html',
	            // 'header' => Yii::t('app','Per session'),
	            // 'value' => function($model) {
                        // $member = MemberTrainings::find()->where(["member_training_id"=>$model->traning_id])->all();
                        // $total = "";
                        // foreach ($member as $data) {
                                // $total = $data->training_total_sessions;
                            // }
                            
                        // $member = MemberTrainings::find()->where(["member_training_id"=>$model->traning_id])->all();
                        // $member_id= "";
                        // foreach ($member as $data) {
                                // $member_id.= $data->member_id.'';
                            // }
                        // $invoice = invoice::find()->where(["member_id"=>$member_id])->all();
                        // $invoice_id = "";
                        // foreach ($invoice as $data) {
                                // $invoice_id .= $data->invoice_id.'<br>';
                            // }
                        // $InvoiceItem = InvoiceItem::find()->where(["invoice_id"=>$invoice_id])->all();
                        // $price ="";
                        // foreach ($InvoiceItem as $data) {
                                // $price = $data->invoice_item_amount;
                            // }
                        // $per_session ="";
                        // if(is_numeric($total) && $total !=0)
                            // $per_session = $price/$total;
                        // return number_format((float)$per_session,0,",",".");
	            // },                   
	    // ],
            // [
	            // 'attribute' => 'book_id',
	            // 'format' => 'html',
	            // 'header' => Yii::t('app','Approval'),
	            // 'value' => function($model) {
                        // $booking= Booking::findOne($model->book_id);
                        // $approval = $booking->book_witness_check;
                        // if($approval==0)
                            // return "No";
                        // if($approval!=0)
                            // return "Yes";
                        
	            // },                   
	    // ],
            // [
	            // 'attribute' => 'member_id',
	            // 'format' => 'html',
	            // 'header' => Yii::t('app','Trainer'),
	            // 'value' => function($model) {
                        // $member = Members::findOne($model->member_id);
                        // return $member->getMemberFullName($model->member_id);
	            // },                   
	    // ],
            // [
	            // 'attribute' => 'member_id',
	            // 'format' => 'html',
	            // 'header' => Yii::t('app','Ss served'),
	            // 'value' => function($model) {
                        // $booking= Booking::findOne($model->book_id);
                        // $approval = $booking->book_witness_check;
                            // if($approval==0)
                                // $approval = 0;
                            // if($approval!=0)
                                // $approval = 1;
                        // return $approval;
	            // },                   
	    // ],
            
                   
        ],
    ]);                  
?>
<?php
    echo '<div class="parkclub-footer" style="text-align: center">';
    echo '<a href='.Yii::$app->urlManager->createUrl('/report/default/pt_session_service_report_pdf?start_date='.$start_date.'&end_date='.$end_date.'&trainer='.urlencode($trainer_selected)).' ><button class="btn btn-success" style = "background-color: rgb(50, 205, 139);">'.Yii::t('app', 'Print Pdf').'</button> </a>';
    echo '<a href='.Yii::$app->urlManager->createUrl('/report/default/pt_session_service_report_excel?start_date='.$start_date.'&end_date='.$end_date.'&trainer='.urlencode($trainer_selected)).' ><button class="btn btn-success" style = "background-color: rgb(50, 205, 139);">'.Yii::t('app', 'Export excel').'</button> </a>';
    echo '</div>';
    ?>
                </div>
            </div>
</div>
<script>
  //  search_booking();
    function search_booking()
    {
        
        var start_date=$('#start_date').val();
        var end_date=$('#end_date').val();
		var trainer = $('#trainer').val();
        
      window.location.href='pt_session_service_report?start_date='+start_date+'&end_date='+end_date+'&trainer='+trainer;
    }
    $('#pt-session').on('scroll', function () {
        $("#pt-session > *").width($("#pt-session").width() + $("#pt-session").scrollLeft());
        });
</script>