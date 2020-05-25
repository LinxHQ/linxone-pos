<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\modules\members\models\Members;
use app\modules\invoice\models\Payment;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use app\models\ListSetup;
use kartik\datetime\DateTimePicker;
use app\modules\revenue_type\models\Revenue;


/* @var $this yii\web\View */
/* @var $searchModel app\modules\invoice\models\InvoiceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$revenue = new Revenue();
$ListSetup = new ListSetup();
$this->title = Yii::t('app', 'Report');
$member = new Members();
// $dropdow_member = array(''=>Yii::t('app', 'All'))+$member->getDataDropdown();
$dropdow_revenue_type = array(''=>Yii::t('app', 'All')) + $ListSetup->getRevenue();
$Method = array(''=>Yii::t('app', 'All'))+ListSetup::getItemByList('Method');
$end_date = date('d/m/Y');
$year_now = date('Y');
$month_now = date('m');
$day_now = date('d');
$house_now = 0;
$mitu_now = 0;
$second_now = 0;
$user = new \app\models\User();
$dropdow_user = $user->getUser(false,\app\models\User::STATUS_ACTIVE);

$dateint = mktime($house_now, $mitu_now, $second_now, $month_now-1,$day_now, $year_now);
$start_date = date('d/m/Y', $dateint); // 02/12/2016
if(isset($_GET['month']) && isset($_GET['year'])){
    $month = $_GET['month']+1;
    $year = $_GET['year'];
    $date_chart = mktime($house_now, $mitu_now, $second_now, $month,01, $year);
        $start_date = date('d/m/Y', $date_chart); 
     
        $end_date = date('t/m/Y', $date_chart); 
        
}
if(isset($_GET['year']) && isset($_GET['a'])){
    $year = $_GET['year'];
    $date_1 = mktime($house_now, $mitu_now, $second_now, 01,01, $year);
        $start_date = date('d/m/Y', $date_1); 
    $date_2 = mktime($house_now, $mitu_now, $second_now, 12,31, $year);
        $end_date = date('d/m/Y', $date_2); 
}
if(isset($_GET['revenue_name'])){
    $revenue_name = $_GET['revenue_name'];
    $revenue = 0;
    foreach($dropdow_revenue_type as $key=>$value){
        
        if($value== $revenue_name){
            $revenue = $key;
        }
    }  
}
$selected_member = (isset($_GET['member'])) ? $_GET['member'] : "";
$selected_start_date = (isset($_GET['start_date'])) ? $_GET['start_date'] : $start_date;
$selected_end_date = (isset($_GET['end_date'])) ? $_GET['end_date'] : $end_date;
$selected_revenue_type = (isset($_GET['revenue_type'])) ? $_GET['revenue_type'] : 0;
$selected_payment_method = (isset($_GET['payment_method'])) ? $_GET['payment_method'] : false;
$selected_user_id = (isset($_GET['user_id'])) ? $_GET['user_id'] : 0;

?>

<div class="parkclub-wrapper parkclub-wrapper-search ">
            <div class="parkclub-rectangle parkclub-shadow">
                <div class="parkclub-rectangle-content" >
                   <div class="parkclub-newm-left-title" >
                        <div class="parkclub-header-left" style = "color: rgb(50, 205, 139);">
                            <?php echo Yii::t('app', 'Payment Report');?>
                        </div>
                    </div>


<?php 
//    if(!$pdf)
//    {
    echo '<table style="text-align:left;" >';
    echo '<tr><td style="padding:10px;">';
    echo '<label class="control-label">'.Yii::t('app', 'Members').': </label></td><td>';
    
    // echo Select2::widget([
        // 'name' => 'id',
        // 'data' => $dropdow_member,
        // 'value' => $selected_member,
        // 'options' => [
    // //        'placeholder' => 'Select member ...',
            // 'width'=>'400px',
            // 'id'=>'member'
// //            'multiple' => true
        // ],
    // ]);
    echo '<input type="text" name="member" id="member" placeholder="'.Yii::t('app','Name').'" value="'.$selected_member.'" />';        
    echo '</td>';

    echo '<td>';
    echo '<label class="control-label" style="padding:10px;">'.Yii::t('app', 'From').': </label></td><td>';
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
    
    echo '<td style="padding:10px;">';
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

    echo '<td><label class="control-label" style="padding:10px;">'.Yii::t('app', 'Revenue type').':</label></td>';
    echo '<td style="width:200px;">';
    echo Select2::widget([
        'name' => 'id123',
        'data' => $dropdow_revenue_type,
        'value' => $selected_revenue_type,
        'options' => [
//            'placeholder' => 'Select revenue type ...',
            'width'=>'400px',
            'id'=>'revenue_type'
//            'multiple' => true
        ],
    ]);
    echo '</td>';

    echo '<td style="padding:10px;"><label class="control-label">'.Yii::t('app', 'Payment type').':</label></td>';
    echo '<td>';
    echo Select2::widget([
        'name' => 'id123',
        'data' => $Method,
        'value' => $selected_payment_method,
        'options' => [
//            'placeholder' => 'Select payment Type ...',
            'width'=>'80px',
            'id'=>'payment_method'
//            'multiple' => true
        ],
    ]);
    echo '</td>';
    
    echo '<td style="padding:10px;"><label class="control-label">'.Yii::t('app', 'Cashier').':</label></td>';
    echo '<td>';
    echo Select2::widget([
        'name' => 'user_id',
        'data' => $dropdow_user,
        'value' => $selected_user_id,
        'options' => [
//            'placeholder' => 'Select Cashier...',
            'width'=>'300px',
            'id'=>'user_id'
//            'multiple' => true
        ],
    ]);
    echo '</td>';

    echo '<td>';
    echo '<button style="margin-left:4px; background-color: rgb(50, 205, 139);" onclick="search_payment();return false;" class="btn btn-success">'.Yii::t('app', 'Search').'</button>';
    echo '</td>';

    echo '</tr>';
    echo '</table>';

?>
        <?php

$payment_Manage = new \app\modules\invoice\models\Payment();
$invoice = new \app\modules\invoice\models\invoice();
$invoice_item = new \app\modules\invoice\models\InvoiceItem();
$ListSetup = new ListSetup();

$total_price =0;
$total_billed =0;
$total_discount_amt =0;
$total_before_tax =0;
$total_tax_amt =0;
$total_total_collection = 0;
    $ListSetup = new ListSetup();
foreach ($dataProvider->models  as $item) {
      $total_total_collection += $item->payment_amount;
}

    $total_total_collection =  $ListSetup->getDisplayPrice($total_total_collection,2);
?>

<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'layout'=>"{items}\n{pager}",
        'showFooter'=>TRUE,
        'footerRowOptions'=>[
            'style'=>'font-weight:bold;',
            
            ],
        'tableOptions' =>['id' => 'payment','class'=>'scroll-report'],
        'columns' => [

        // stt
            [
                'class' => 'yii\grid\SerialColumn', 
                'header'=>Yii::t('app','No.'),
                'contentOptions'=>['style'=>'width: 5%;text-align: center;']
            ],

            [
              'attribute' => 'payment_date',
              'format' => 'html',
              'header'=>Yii::t('app', 'Receipt date'),
              'value' => function($model) use($ListSetup) {
                      return $ListSetup->getDisplayDateTime($model->payment_date);
              },
              'footer'=>'Total'
      ],
            [
              'attribute' => 'payment_date',
              'format' => 'html',
                    'header'=>Yii::t('app', 'Receipt number'),
              'value' => function($model) {
                       return $model->payment_no;
              },
      ],       
            [
              'attribute' => 'payment_date',
              'format' => 'html',
                    'header'=>Yii::t('app', 'Invoice number'),
              'value' => function($model)  {
                      // $invoice = $invoice->findOne($model->invoice_id);
                       // return $invoice->invoice_no;
					$invoice_url=Yii::$app->urlManager->createUrl('invoice/default/update?id='.$model->invoice->invoice_id);
                    return '<a href="'.$invoice_url.'">'.$model->invoice->invoice_no.'</a>'; 
              },
      ],
            [
              'attribute' => 'invoice_id',
              'format' => 'html',
                    'header'=>Yii::t('app', 'Member Name'),
              'value' => function($model) {
                       // $invoice = $invoice->findOne($model->invoice_id);
                       $member = new Members();
                       if($member)
                            return "<a href='".Yii::$app->urlManager->createUrl('members/default/update?id='.$model->invoice->member_id)."'>".$member->getMemberFullName($model->invoice->member_id)."</a>";
              },
                    
      ],
            [
              'attribute' => 'invoice_id',
              'format' => 'html',
                    'header'=>Yii::t('app', 'Member Barcode'),
              'value' => function($model) {
                       // $invoice = $invoice->findOne($model->invoice_id);
                       $member = Members::findOne($model->invoice->member_id);
                       if($member)
                            return $member->member_barcode;
              },
                    
      ],
        [
                    'attribute' => 'invoice_type_id',
                    'format' => 'html',
                    'header' => Yii::t('app', 'Description'),
                    'value' => function($model) use ($invoice_item){
                            // $invoice_type = $invoice->findOne($model->invoice_id)->invoice_type;
							$invoice_type = $model->invoice->invoice_type;
                            if($invoice_type == "pos"){
                                return "Pos";
                            }else{
                            $invoiceItem = $invoice_item->find()->where(["invoice_id"=>$model->invoice_id])->one();
                            if($invoiceItem)
                                return $invoiceItem->invoice_item_description;
                            return "";
                            }
                    },

            ],
            [
                    'attribute' => 'invoice_type_id',
                    'format' => 'html',
                    'header' => Yii::t('app', 'Period'),
                    'value' => function($model) use ($invoice_item){
                        $MembershipPrice = new app\modules\membership_type\models\MembershipPrice();
                        $invoiceItem = $invoice_item->find()->where(["invoice_id"=>$model->invoice_id])->one();
                        // $invoice = $invoice->findOne($model->invoice_id);
                        $date_apply = "";
						if($invoiceItem) {
							if($invoiceItem && !in_array($invoiceItem->invoice_item_description, $invoice_item->getArrayItemInvoice()))
							{
								$membership = $model->invoice->invoice_type_id;
								if($membership>0 && $model->invoice->invoice_type == "membership")
								{
									$membershipInfo = \app\modules\members\models\Membership::findOne($membership);
									if($membershipInfo)
										$date_apply = $MembershipPrice->getDateMemberTypeApplyPrice($membershipInfo->membership_type_id, $model->invoice->invoice_date);
								}
							}
						}
                        return $date_apply;
                                
                    }
            ],
            [
                    'attribute' => 'invoice_type_id',
                    'format' => 'html',
                    'header' => Yii::t('app', 'Months'),
                    'value' => function($model) use ($invoice_item){
                        $listsetup = new ListSetup();
                        $MembershipPrice = new app\modules\membership_type\models\MembershipPrice();
                        $invoiceItem = $invoice_item->find()->where(["invoice_id"=>$model->invoice_id])->one();
                        // $invoice = $invoice->findOne($model->invoice_id);
                        $Months = "";
                        if($invoiceItem) {
							if(!in_array($invoiceItem->invoice_item_description, $invoice_item->getArrayItemInvoice()))
							{
								$membership = $model->invoice->invoice_type_id;
								if($membership>0 && $model->invoice->invoice_type == "membership")
								{
									$membershipInfo = \app\modules\members\models\Membership::findOne($membership);
									if($membershipInfo){
										$membershipType = app\modules\membership_type\models\MembershipType::findOne($membershipInfo->membership_type_id);
										if($membershipType)
											if($membershipType->membership_type_month==0)
												$Months = "";
											else
												$Months = ListSetup::getItemByList('memberShipType_status')[$membershipType->membership_type_month];
									}
								}
							}
						}
                        return $Months;
                    },

            ],      
            [
              'attribute' => 'invoice_id',
              'format' => 'html',
              'header'=>Yii::t('app', 'Unit price'),
              'value' => function($model) use ($invoice_item, $ListSetup){
                // $invoice_type = $invoice->findOne($model->invoice_id)->invoice_type;
				$invoice_type = $model->invoice->invoice_type;
                if($invoice_type == "pos"){
                    return "";
                }else{
                    $amount=0;
                    $invoiceItem = $invoice_item->find()->where(["invoice_id"=>$model->invoice_id])->one();
                    if(isset($invoiceItem)){
                        $amount = $invoiceItem->invoice_item_price;
                    }
                        return $ListSetup->getDisplayPrice($amount,2);
                    }
              },
//              'footer'=>$total_price
                    
      ],
            [
              'attribute' => 'invoice_id',
              'format' => 'html',
                    'header'=>Yii::t('app', 'Quantity'),
              'value' => function($model) use ($invoice_item){
                // $invoice_type = $invoice->findOne($model->invoice_id)->invoice_type;
				$invoice_type = $model->invoice->invoice_type;
                $invoice_item_quantity = 0;
                if($invoice_type == "pos"){
                    return "";
                }else{
                    $invoiceItem = $invoice_item->find()->where(["invoice_id"=>$model->invoice_id])->one();
                    if(isset($invoiceItem)){
                        $invoice_item_quantity = $invoiceItem->invoice_item_quantity;                        
                    }
                    return number_format($invoice_item_quantity);
                }
              },
                    
      ],
            [
              'attribute' => 'invoice_id',
              'format' => 'html',
              'header'=>Yii::t('app', 'Total billed'),
              'value' => function($model) use ($ListSetup){
                    // $invoice_type = $invoice->findOne($model->invoice_id)->invoice_type;
					$invoice_type = $model->invoice->invoice_type;
                        if($invoice_type == "pos"){
                            // $result = $invoice->findOne($model->invoice_id)->invoice_subtotal;
							$result = $model->invoice->invoice_subtotal;
                        }else{
                            // $result = $invoice->getTotalBill($model->invoice_id);
							$result = $model->invoice->getTotalBill($model->invoice_id);
                        }
                    
                  return $ListSetup->getDisplayPrice($result,2);
              },
//                      'footer'=>$total_billed
                    
      ],
            [
              'attribute' => 'invoice_id',
              'format' => 'html',
               'header'=>Yii::t('app', 'Incentives/Discount amt.'),
              'value' => function($model) use ( $ListSetup){
                    $invoice_type = $model->invoice->invoice_type;
					if($invoice_type == "pos"){
						$result = $model->invoice->invoice_subtotal - $model->invoice->invoice_total_last_discount;
					}else{
						$result = $model->invoice->getDiscount($model->invoice_id);
					}
                    return $ListSetup->getDisplayPrice($result,2);
              },
//                      'footer'=>$total_discount_amt
                    
      ],
            [
              'attribute' => 'invoice_id',
              'format' => 'html',
              'header'=>Yii::t('app', 'Payable'),
              'value' => function($model) use ( $ListSetup){
                  // $invoice_type = $invoice->findOne($model->invoice_id)->invoice_type;
				    $invoice_type = $model->invoice->invoice_type;
                        if($invoice_type == "pos"){
                            $result = $model->invoice->invoice_total_last_discount;
                        }else{
                            $result = $model->invoice->getBeforeTax($model->invoice_id);
                        }
                    return $ListSetup->getDisplayPrice($result,2);
              },
//                      'footer'=>$total_before_tax
                    
      ],
            [
              'attribute' => 'invoice_id',
              'format' => 'html',
              'header'=>Yii::t('app', 'VAT included'),
              'value' => function($model) use ( $ListSetup){
                    // $result = $invoice->findOne($model->invoice_id)->invoice_total_last_tax;
					$result = $model->invoice->invoice_total_last_tax;
                    return $ListSetup->getDisplayPrice($result,2);
              },           
      ],

            [
              'attribute' => 'invoice_id',
              'format' => 'html',
                    'header'=>Yii::t('app', 'Total collection'),
              'value' => function($model) use ($ListSetup) {
                  $total_collection = $model->payment_amount;
                  return $ListSetup->getDisplayPrice($total_collection,2);
              },
                      'footer'=>$total_total_collection
                    
      ],
            [
              'attribute' => 'invoice_id',
              'format' => 'html',
                    'header'=>Yii::t('app', 'Cashier'),
              'value' => function($model) {
                  $user = new \app\models\User();
                  return $user->getFullName($model->created_by);
              },
      ],         
            [
              'attribute' => 'invoice_id',
              'format' => 'html',
                    'header'=>Yii::t('app', 'Remark'),
              'value' => function($model) {
                      // $invoice = $invoice->findOne($model->invoice_id);
                      if($model->invoice->invoice_status == app\modules\invoice\models\invoice::INVOICE_STATUS_VOID_INVOICE)
                            return app\modules\invoice\models\invoice::INVOICE_STATUS_VOID_INVOICE;
                      else if($model->payment_void==1)
                          return Payment::PAYMENT_VOID_LABEL;
                      else
                          return "";
              },
                    
      ],
        ],
    ]); 
echo '<div class="parkclub-footer" style="text-align: center">';                  
echo '<a target="_blank" href="'.Yii::$app->urlManager->createUrl('/report/default/pdfpaymentreport').'" ><button class="btn btn-success" style = "background-color: rgb(50, 205, 139);">'.Yii::t('app','Daily payment report').'</button> </a>'      ;              
echo '<a target="_blank" href="'.Yii::$app->urlManager->createUrl('/report/default/pdfpayment?start_date='.$selected_start_date.'&end_date='.$selected_end_date.'&member='.urlencode($member_search).'&revenue_type='.$revenue_type.'&user_id='.$user_id.'&payment_method='.$payment_method).'" ><button class="btn btn-success" style = "background-color: rgb(50, 205, 139);">'.Yii::t('app','Print PDF').'</button> </a>'      ;              
echo '<a target="_blank" href="'.Yii::$app->urlManager->createUrl('/report/default/excelpayment?start_date='.$selected_start_date.'&end_date='.$selected_end_date.'&member='.urlencode($member_search).'&revenue_type='.$revenue_type.'&user_id='.$user_id.'&payment_method='.$payment_method).'" ><button class="btn btn-success" style = "background-color: rgb(50, 205, 139);">'.Yii::t('app','Export excel').'</button> </a>' ;                   
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
                            <?php echo Yii::t('app', 'Payment Chart');?>
                        </div>
                       <div class="parkclub-header-right">
                           <?php echo Yii::t('app', 'Year');?><?php echo yii\bootstrap\Html::dropDownList('change-year',date('Y'),$ListSetup->year(),['onchange'=>'load_chart_year(this.value)','style'=>'width:30%']); ?>
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
                            <?php echo Yii::t('app', 'Revenue Chart');?>
                        </div>
                       <div class="parkclub-header-right">
                           <?php echo Yii::t('app', 'Month');?><?php echo yii\bootstrap\Html::dropDownList('change-month',"",array(''=>Yii::t('app','All'))+$ListSetup->getMonth(1),['onchange'=>'load_chart_revenue();return false','style'=>'width:30%','id'=>'month-revenue']); ?>
                           <?php echo Yii::t('app', 'Year');?><?php echo yii\bootstrap\Html::dropDownList('change-year',date('Y'),array(''=>Yii::t('app','All'))+$ListSetup->year(),['onchange'=>'load_chart_revenue();return false;','style'=>'width:30%','id'=>'year-revenue']); ?>
                       </div>
                    </div>
                    <br>
                    <div id="revenue-chart" style="min-height: 500px;">
                    </div>
                </div>
            </div>
</div>


<script>
//    $(document).ready(function() {
//        search_payment();
//    });
    $( document ).ready(function() {
        var intall_data = '<?php echo Yii::$app->session['install-data']; ?>';
        var istour = '<?php echo Yii::$app->session['tour']; ?>';
        if((intall_data==2 || intall_data==1) && istour==1){
            tour_no_demo.end();
            data_demo.end();
        }
        
        load_chart_year('<?php echo date('Y'); ?>');
        load_chart_revenue();
    })
    function search_payment()
    {
        var member=$('#member').val();
        var revenue_type=$('#revenue_type').val();
        var start_date=$('#start_date').val();
        var end_date=$('#end_date').val();
        var payment_method=$('#payment_method').val();
        var user_id = $('#user_id').val();
        window.location.href='paymentreport?start_date='+start_date+'&end_date='+end_date+'&member='+member+'&revenue_type='+revenue_type+'&payment_method='+payment_method+'&user_id='+user_id;
        
    }
    function load_chart_year(year){
        $.blockUI();
        $('#live-chart').load('<?php echo Yii::$app->urlManager->createUrl('report/default/chart-payment') ?>',{year:year},function(){
            $.unblockUI();
        });
    }
    
    function load_chart_revenue(){
        $.blockUI();
        var month = $('#month-revenue').val();
        var year = $('#year-revenue').val();
        $('#revenue-chart').load('<?php echo Yii::$app->urlManager->createUrl('report/default/chart-revenue') ?>',{month:month,year:year},function(){
            $.unblockUI();
        });
    }
 
    $('#payment').on('scroll', function () {
        $("#payment > *").width($("#payment").width() + $("#payment").scrollLeft());
        });
</script>
