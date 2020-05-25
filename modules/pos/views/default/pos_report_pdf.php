<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\modules\members\models\Members;
use app\modules\invoice\models\Payment;
use kartik\date\DatePicker;
use app\modules\invoice\models\invoice;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\invoice\models\InvoiceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Pos');
$total_amount = 0;
$total_qty = 0;
$invoi = new invoice();
foreach ($dataProvider->models  as $item) {
        $invoice = app\modules\invoice\models\invoice::findOne($item->invoice_id);
        $total_qty += $item->invoice_item_quantity;
        $total_amount += $invoi->getAmountLastTaxItem($invoice->invoice_gst_value,$item->invoice_item_amount,$invoice->invoice_discount);
    }
?>
<link href='<?php echo Yii::$app->urlManager->baseUrl;?>/css/pdf.css' rel='stylesheet' type='text/css'>
<div class="park-header">
    <div class="pdf_head_sogan"><?php echo YII::$app->params['sogan_report'] ?></div>
    <br>
    <div class="pdf_head_title"><?php echo Yii::t('app','POS report'); ?></div> 
    <div class="pdf_head_date"><?php echo Yii::t('app','From')?>: <?php  echo $view_start_date; ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo Yii::t('app','To')?>: <?php echo $view_end_date; ?></div>
<br>
</div>
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
                                    return date('d/m/Y  H:i:s',  strtotime($invoice->invoice_date));
                                return "";
                            }
                        ], 
						
						[
                            'attribute'=>'invoice_item_id',
                            'header' => Yii::t('app', 'Bill Time'),
                            'value'=>function($model){
                                $payment = Payment::findOne($model->payment_id);
                                if($payment)
                                    return date('d/m/Y  H:i:s',  strtotime($payment->payment_date));
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
?>

