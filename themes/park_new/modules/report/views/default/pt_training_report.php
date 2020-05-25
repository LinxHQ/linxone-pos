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
use app\modules\training\models\MemberTrainers;
use app\modules\training\models\MemberTrainings;
use app\modules\invoice\models\InvoiceItem;
use app\modules\invoice\models\Invoice;
use app\modules\training\models\MemberTrainingsSearch;

$modelTraining = new MemberTrainingsSearch();
$training = $modelTraining->search(Yii::$app->request->queryParams);
$book = new app\modules\booking\models\Booking();

?>
<div id="members-index" class="parkclub-wrapper parkclub-wrapper-search ">
            <div class="parkclub-rectangle parkclub-shadow">
                <div class="parkclub-rectangle-content">
                   <div class="parkclub-newm-left-title" >
                        <div class="parkclub-header-left" style = "color: rgb(50, 205, 139);">
                            <?php echo Yii::t('app','PT TRAINING REPORT');?>
                        </div>
                    </div>


<?php 

  echo '<table>';
    echo '<tr>';
   

    echo '<td>';
    echo '<label class="control-label">'.Yii::t('app','From').' : </label></td><td>';
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
    echo '<label class="control-label">'.Yii::t('app','To').' : </label></td><td>';
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
    echo '<button style="margin-left:40px; background-color: rgb(50, 205, 139);" onclick="search_booking();return false;" class="btn btn-success">'.Yii::t('app','Search').'</button>';
    echo '</td>';

    echo '</tr>';
    echo '</table>';
    
$payment_Manage = new \app\modules\invoice\models\Payment();
$invoice = new \app\modules\invoice\models\invoice();
$ListSetup  = new app\models\ListSetup();

?>
<?=GridView::widget([
        'dataProvider' => $dataProvider,
        'layout'=>"{items}",
        'showFooter'=>FALSE,
        'footerRowOptions'=>[
            'style'=>'font-weight:bold;'
            ],
        'tableOptions' =>['class'=>'scroll-report', 'id'=>'pt-training'],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn', 'header'=>Yii::t('app','No.')],
            [
	            'attribute' => 'book_id',
	            'format' => 'html',
	            'header' => Yii::t('app','Create date'),
	            'value' => function($model) {
                        return date("d/m/Y", strtotime($model->create_date));
	            },
                   
	    ],
            [
	            'attribute' => 'member_id',
	            'format' => 'html',
	            'header' => Yii::t('app','Member barcode'),
	            'value' => function($model) {
                        $member_training = MemberTrainings::findOne($model->member_training_id);
                        $member_id= "";
                        if($member_training) {
                                $member_id = $member_training->member_id;
                            }
                            
                        $member = Members::findOne($member_id);
                        $member_barcode ="";
                        if($member) {
                                $member_barcode = $member->member_barcode;
                            }
        
                        return $member_barcode;
	            },
                   
	    ],
            [
	            'attribute' => 'member_id',
	            'format' => 'html',
	            'header' => Yii::t('app','Member name'),
	            'value' => function($model) {
                        $member_training = MemberTrainings::findOne($model->member_training_id);
                        $member_id= "";
                        if($member_training) {
                                $member_id = $member_training->member_id;
                            }
                            
                        $member = Members::findOne($member_id);
                        $member_name="";
                        if($member) {
                                $member_name = $member->getMemberFullName($member_id);
                            }
        
                        return "<a href='".Yii::$app->urlManager->createUrl('members/default/update?id='.$member_id)."'>".$member_name."</a>";
	            },
                   
	    ],
            [
	            'attribute' => 'member_id',
	            'format' => 'html',
	            'header' => Yii::t('app','Training type'),
	            'value' => function($model) {
                        $member_training = MemberTrainings::findOne($model->member_training_id);
                        $training_type_id= "";
                        if($member_training) {
                                $training_type_id = $member_training->package_id;
                            }
                        $listsetup = new app\models\ListSetup();
                        if($training_type_id>0){
                            $revenue = new \app\modules\revenue_type\models\RevenueItem();
                            $package_arr = $revenue->getRevenueItemByEntry(2,'array','index');
                            return $training_type = (isset($package_arr[$training_type_id]) ? $package_arr[$training_type_id] : "");             
                        }else{
                            return $training_type_id;
                        }
	            },                   
	    ],
            [
	            'attribute' => 'member_id',
	            'format' => 'html',
	            'header' => Yii::t('app','Total session'),
	            'value' => function($model) {
                        $member_training = MemberTrainings::findOne($model->member_training_id);
                        $total = "";
                        if($member_training) {
                                $total = $member_training->training_total_sessions;
                            }
                             return $total;
                        
	            },                   
	    ],
            [
	            'attribute' => 'invoice_id',
	            'format' => 'html',
	            'header' => Yii::t('app','Price'),
	            'value' => function($model) {
                        // $member_training = MemberTrainings::findOne($model->member_training_id);
                        // $member_id= "";
                        // if($member_training) {
                        //         $member_id = $member_training->member_id;
                        //     }
                        // $invoice = invoice::find()->where(["member_id"=>$member_id])->all();
                        // $invoice_id = "";
                        // foreach ($invoice as $data) {
                        //         $invoice_id .= $data->invoice_id.'<br>';
                        //     }
                        // $InvoiceItem = InvoiceItem::find()->where(["invoice_id"=>$invoice_id])->all();
                        // $price ="";
                        // foreach ($InvoiceItem as $data) {
                        //         $price = $data->invoice_item_amount;
                        //     }
                        
                       
                        // return number_format((float)$price,0,",",".");
                    $invoice = new \app\modules\invoice\models\invoice();
                    $invoice_item = new \app\modules\invoice\models\InvoiceItem();
                    $total_amount = 0;
                    $invoice_tranning = $invoice->getInvoiceOneByEntry($model->member_training_id, 'Trainer');
                    if($invoice_tranning){
                        $total_amount = $invoice_item->getAmountInvoice($invoice_tranning->invoice_id);
                    }
                    return number_format((float)$total_amount,0,",",".");
	            },                   
	    ],
            [
	            'attribute' => 'member_id',
	            'format' => 'html',
	            'header' => Yii::t('app','Per session'),
	            'value' => function($model) {
                        $member_training = MemberTrainings::findOne($model->member_training_id);
                        $total = "";
                        $member_id= "";
                        if($member_training) {
                                $total = $member_training->training_total_sessions;
                                $member_id = $member_training->member_id;
                            }
                            
                        
                        $invoice = invoice::find()->where(["member_id"=>$member_id])->all();
                        $invoice_id = "";
                        foreach ($invoice as $data) {
                                $invoice_id .= $data->invoice_id.'<br>';
                            }
                        $InvoiceItem = InvoiceItem::find()->where(["invoice_id"=>$invoice_id])->all();
                        $price ="";
                        foreach ($InvoiceItem as $data) {
                                $price = $data->invoice_item_amount;
                            }
                        $per_session ="";
                        if(is_numeric($total) && $total !=0)
                            $per_session = $price/$total;
                        return number_format((float)$per_session,0,",",".");
	            },                   
	    ],
            [
	            'attribute' => 'book_id',
	            'format' => 'html',
	            'header' => Yii::t('app','Total SS served'),
	            'value' => function($model) {
                        $book = new app\modules\booking\models\Booking();
                        $user_ss =0;
                        if($model){
                            $re_ss = $model->getRemainingSession($model->member_training_id);
                            $user_ss = $model->training_total_sessions - $re_ss;
                        }
   
                        return $user_ss;
                        
	            },                   
	    ],
                           
            [
	            'attribute' => 'book_id',
	            'format' => 'html',
	            'header' => Yii::t('app','Remaining ss'),
	            'value' => function($model) {
                        $re_ss = $model->getRemainingSession($model->member_training_id);
                            return $re_ss;
	            },                   
	    ],
            [
	            'attribute' => 'member_id',
	            'format' => 'html',
	            'header' => Yii::t('app','Trainer'),
	            'value' => function($model) {
                        $member = new Members();
                        $member_trainer = MemberTrainers::findOne($model->member_training_id);
                        return $member->getMemberFullName($member_trainer->trainer_user_id);
	            },                   
	    ],    
        ],
    ]);                  
?>
<?php
    echo '<div class="parkclub-footer" style="text-align: center">';
    echo '<a href='.Yii::$app->urlManager->createUrl('/report/default/pt_training_report_pdf?start_date='.$start_date.'&end_date='.$end_date).' ><button class="btn btn-success" style = "background-color: rgb(50, 205, 139);">'.Yii::t('app','Print Pdf').'</button> </a>';
    echo '<a href='.Yii::$app->urlManager->createUrl('/report/default/pt_training_report_excel?start_date='.$start_date.'&end_date='.$end_date).' ><button class="btn btn-success" style = "background-color: rgb(50, 205, 139);">'.Yii::t('app','Export excel').'</button> </a>';
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
        
      window.location.href='pt_training_report?start_date='+start_date+'&end_date='+end_date;
    }
    $('#pt-training').on('scroll', function () {
        $("#pt-training > *").width($("#pt-training").width() + $("#pt-training").scrollLeft());
        });
</script>