<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\modules\members\models\Members;
use app\modules\invoice\models\Payment;
use kartik\date\DatePicker;
use app\modules\invoice\models\invoice;
use kartik\select2\Select2;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\invoice\models\InvoiceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'pos report');
$invoice = new invoice();
$dropdow_invoice_status = array('All'=>Yii::t('app','All')) + $invoice->getDropdownStatus() + array('Void Invoice'=>Yii::t('app','Void Invoice'));
// $dropdow_invoice_status = $invoice->getDropdownStatus() ;
$invoice_status_selected ="All";
if(isset($_GET['status'])){
    $invoice_status_selected = $status;
}
$selected_start_date = (isset($_GET['start_date'])) ? $_GET['start_date'] : $start_date;
$selected_end_date = (isset($_GET['end_date'])) ? $_GET['end_date'] : $end_date;

$total_amount = 0;
$total_qty = 0;
$invoi = new invoice();
foreach ($dataProvider->models  as $item) {
        $invoice = app\modules\invoice\models\invoice::findOne($item->invoice_id);
        $total_qty += $item->invoice_item_quantity;
        $total_amount += $invoi->getAmountLastTaxItem($invoice->invoice_gst_value,$item->invoice_item_amount,$invoice->invoice_discount);
    }
?>
<div id="members-index" >

               
<?php 
echo '<table style ="margin-top: 3%;">';
    echo '<tr>';
    

    echo '<td>';
    echo '<label class="control-label" >'.Yii::t('app', 'From').': </label></td><td>';
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
    
    echo '<td >';
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
    
    echo '<td><label class="control-label">'.Yii::t('app','Status').':</label></td>';
    echo '<td style = "width:200px;">';
    echo Select2::widget([
        'name' => 'id1334',
        'data' => $dropdow_invoice_status,
        'value' => $invoice_status_selected,
        'options' => [
            'id'=>'invoice_status'
        ],
    ]);
    echo '</td>';
    
    echo '<td>';  
    echo '<button style="margin-left:4px; margin-bottom: 10px;background-color: rgb(50, 205, 139);" onclick="search_pos();return false;" class="btn btn-success">'.Yii::t('app', 'Search').'</button>';
    echo '</td>';

    echo '</tr>';
    echo '</table>';


$payment_Manage = new \app\modules\invoice\models\Payment();
$invoice = new \app\modules\invoice\models\invoice();
$ListSetup  = new app\models\ListSetup();
?>

<?=GridView::widget([
        'dataProvider' => $dataProvider,
        'layout'=>"{items}\n{pager}",
        'showFooter'=>true,
        'footerRowOptions'=>[
            'style'=>'font-weight:bold;',
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn', 'header'=>Yii::t('app','No.')],
            
                        [
                            'attribute'=>'invoice_item_id',
                            'header' => Yii::t('app', 'Invoice Time'),
                            'value'=>function($model){
                                $invoice = app\modules\invoice\models\invoice::findOne($model->invoice_id);
                                if($invoice)
                                    return date('d/m/Y H:i:s',  strtotime($invoice->invoice_date));
                                return "";
                            }
                        ], 
						
						[
                            'attribute'=>'invoice_item_id',
                            'header' => Yii::t('app', 'Bill Time'),
                            'value'=>function($model){
                                $payment = Payment::findOne($model->payment_id);
                                if($payment)
                                    return date('d/m/Y H:i:s',  strtotime($payment->payment_date));
                                return "";
                            }
                        ], 
                                
                        [
                            'attribute'=>'invoice_item_id',
                            'header' => Yii::t('app', 'Invoice no'),
                            'value'=>function($model){
                                $invoice = app\modules\invoice\models\invoice::findOne($model->invoice_id);
                                if($invoice)
                                    return $invoice->invoice_no;
                                return "";
                            },
                            'footer'=>'<b>'.Yii::t('app', 'Total').'</b>'
                        ], 
                                    
                        [
                            'attribute'=>'invoice_item_id',
                            'header' => Yii::t('app', 'Product name'),
                            'value'=>function($model){
                                return $model->invoice_item_description;
                            },
                        ],
                                    
                        [
                            'attribute'=>'invoice_item_id',
                            'header' => Yii::t('app', 'Quantity'),
                            'value'=>function($model){
                                return $model->invoice_item_quantity;
                            },
                            'footer'=>'<b>'.$total_qty.'</b>'
                        ],
                                
                        [
                            'attribute'=>'invoice_item_id',
                            'header' => Yii::t('app', 'Price'),
                            'value'=>function($model){
                                return app\models\ListSetup::getDisplayPrice($model->invoice_item_price, 2);
                            },
                        ],
                        [
                            'attribute'=>'invoice_item_id',
                            'header' => Yii::t('app', 'Discount'),
                            'value'=>function($model){
                                $invoice = app\modules\invoice\models\invoice::findOne($model->invoice_id);
                                if($invoice){
                                    return $invoice->invoice_discount." %";
                                }
                            },
                        ],
                               
                        [
                            'attribute'=>'invoice_item_id',
                            'header' => Yii::t('app', 'Amount'),
                            'value'=>function($model){
                                $invoice = app\modules\invoice\models\invoice::findOne($model->invoice_id);
                                if($invoice){
                                    $invoi = new app\modules\invoice\models\invoice();
                                    $amount = $invoi->getAmountLastTaxItem($invoice->invoice_gst_value,$model->invoice_item_amount,$invoice->invoice_discount);
                                    return app\models\ListSetup::getDisplayPrice($amount, 2);
                                }
                                return "";
                            },
                            'footer'=>'<b>'. app\models\ListSetup::getDisplayPrice($total_amount, 2).'</b>'
                        ],
                        [
                            'attribute'=>'invoice_item_id',
                            'header' => Yii::t('app', 'Status'),
                            'value'=>function($model){
                                $invoice = app\modules\invoice\models\invoice::findOne($model->invoice_id);
                                if($invoice){
                                    return Yii::t('app',$invoice->invoice_status);
                                }
                            },
                        ],
                     
        ],
    ]);  
     
echo '<div class="parkclub-footer" style="text-align: center">';
if(isset($_GET['sesstion'])){
    echo '<a href='.Yii::$app->urlManager->createUrl('/report/default/pos_report_pdf?start_date='.$selected_start_date.'&end_date='.$selected_end_date.'&sesstion='.$_GET['sesstion']).' ><button class="btn btn-success" style = "background-color: rgb(50, 205, 139);">'.Yii::t('app', 'Print PDF').'</button> </a>';                    
    echo '<a href='.Yii::$app->urlManager->createUrl('/report/default/pos_report_excel?start_date='.$selected_start_date.'&end_date='.$selected_end_date.'&sesstion='.$_GET['sesstion']).' ><button class="btn btn-success" style = "background-color: rgb(50, 205, 139);" >'.Yii::t('app', 'Export Excel').'</button> </a>';
}else{
    echo '<a href='.Yii::$app->urlManager->createUrl('/report/default/pos_report_pdf?start_date='.$selected_start_date.'&end_date='.$selected_end_date.'&status='.$invoice_status_selected).' ><button class="btn btn-success" style = "background-color: rgb(50, 205, 139);">'.Yii::t('app', 'Print PDF').'</button> </a>';                    
    echo '<a href='.Yii::$app->urlManager->createUrl('/report/default/pos_report_excel?start_date='.$selected_start_date.'&end_date='.$selected_end_date.'&status='.$invoice_status_selected).' ><button class="btn btn-success" style = "background-color: rgb(50, 205, 139);" >'.Yii::t('app', 'Export Excel').'</button> </a>'; 
}                  
echo '</div';

?>
</div>

<!-- MODAL VIEW INVOICE -->
    <div id="bs-model-table" data-backdrop="static" data-keyboard="false" class="modal fade bs-example-modal-lg popupCheckin" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
        <div class="modal-dialog modal-lg" role="document" style="width: 600px;">
            <div  class="modal-content">
                <div class="modal-header" style="background-color: rgb(50, 205, 139); color:#fff;">
                    <button class="close" type="button" data-dismiss="modal" aria-hidden="true">×</button>
               <div class="fa fa-2x fa-calendar-plus-o" style="float:left;margin-right:10px"></div>
               <div>
                   <h4 class="modal-title"><?php echo YII::t('app', "New table") ?></h4>
               </div>
               </div>
                <div class="modal-body" id="modal-content-invoice" >
                </div>
            </div>
        </div>
    </div>
    <input id = "isset" type="hidden" <?php if(isset(Yii::$app->user->identity->menu)){ ?>value =<?php echo Yii::$app->user->identity->menu;} ?>>
</div>
<!-- END MODAL VIEW INVOICE -->
<script>
    function search_pos()
    {
        var status = $('#invoice_status').val();
        var start_date = $('#start_date').val();
        var end_date = $('#end_date').val();
        window.location.href='pos_report?start_date='+start_date+'&end_date='+end_date+'&status='+status+'&tab=1';
    }
    
    function popViewInvoice(id){
        $('.modal-content').css({'min-height':'200px'});
        $('#modal-content-invoice').load('<?php echo Yii::$app->urlManager->createUrl('invoice/default/get-item-by-invoice-id'); ?>?id='+id,
            function(data){
                $('#bs-model-table .modal-title').html("<?php echo Yii::t('app','View invoice'); ?>");
                $('#bs-model-table').modal('show'); 
            });
    }
    </script>