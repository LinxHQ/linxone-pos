<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\modules\members\models\Members;
use app\modules\invoice\models\Payment;
use kartik\date\DatePicker;
use app\models\ListSetup;
use app\modules\invoice\models\InvoiceItem;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\invoice\models\InvoiceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

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
    
//    $invoiceItem = InvoiceItem::find()->where(["invoice_id"=>$item->invoice_id])->one();
//    if(isset($invoiceItem)){
//        $total_price += $invoiceItem->invoice_item_price;
//    }
    
//    $invoice = new app\modules\invoice\models\invoice();
//    $total_billed += $invoice->getTotalBill($item->invoice_id);
//    
//    $total_discount_amt += $invoice->getDiscount($item->invoice_id);
//    
//    $total_before_tax += $invoice->getBeforeTax($item->invoice_id);
//    
//    $beforeTax = $invoice->getBeforeTax($item->invoice_id);
//    $tax_arr=$ListSetup->Tax;
//    $invoice_tax = app\modules\invoice\models\invoice::findOne($item->invoice_id);
//    $tax_value=$tax_arr[$invoice_tax->invoice_gst];
//    $total_tax_amt += ($tax_value * $beforeTax)/100;
     
      $total_total_collection += $item->payment_amount;
}
//    $total_price = number_format($total_price,0,",",".");
//    $total_billed = number_format($total_billed,0,",",".");
//    $total_before_tax = number_format($total_before_tax,0,",",".");
//    $total_discount_amt = number_format($total_discount_amt,0,",",".");
//    $total_tax_amt = number_format($total_tax_amt,0,",",".");
    $total_total_collection =  $ListSetup->getDisplayPrice($total_total_collection,2);
?>
<div>
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
        ['class' => 'yii\grid\SerialColumn', 'header'=>Yii::t('app','No.')],

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
              'value' => function($model) use ($invoice) {
                      $invoice = $invoice->findOne($model->invoice_id);
                       return $invoice->invoice_no;
              },
      ],
            [
              'attribute' => 'invoice_id',
              'format' => 'html',
                    'header'=>Yii::t('app', 'Member Name'),
              'value' => function($model) use ($invoice){
                       $invoice = $invoice->findOne($model->invoice_id);
                       $member = new Members();
                       if($member)
                            return $member->getMemberFullName($invoice->member_id);
              },
                    
      ],
            [
              'attribute' => 'invoice_id',
              'format' => 'html',
                    'header'=>Yii::t('app', 'Member Barcode'),
              'value' => function($model) use ($invoice){
                       $invoice = $invoice->findOne($model->invoice_id);
                       $member = Members::findOne($invoice->member_id);
                       if($member)
                            return $member->member_barcode;
              },
                    
      ],
        [
                    'attribute' => 'invoice_type_id',
                    'format' => 'html',
                    'header' => Yii::t('app', 'Description'),
                    'value' => function($model) use ($invoice_item, $invoice){
                            $invoice_type = $invoice->findOne($model->invoice_id)->invoice_type;
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
                    'value' => function($model) use ($invoice_item,$invoice){
                        $MembershipPrice = new app\modules\membership_type\models\MembershipPrice();
                        $invoiceItem = $invoice_item->find()->where(["invoice_id"=>$model->invoice_id])->one();
                        $invoice = $invoice->findOne($model->invoice_id);
                        $date_apply = "";
                        if(!in_array($invoiceItem->invoice_item_description, $invoice_item->getArrayItemInvoice()))
                        {
                            $membership = $invoice->invoice_type_id;
                            if($membership>0 && $invoice->invoice_type == "membership")
                            {
                                $membershipInfo = \app\modules\members\models\Membership::findOne($membership);
                                if($membershipInfo)
                                    $date_apply = $MembershipPrice->getDateMemberTypeApplyPrice($membershipInfo->membership_type_id, $invoice->invoice_date);
                            }
                        }
                        return $date_apply;
                                
                    }
            ],
            [
                    'attribute' => 'invoice_type_id',
                    'format' => 'html',
                    'header' => Yii::t('app', 'Months'),
                    'value' => function($model) use ($invoice_item,$invoice){
                        $listsetup = new ListSetup();
                        $MembershipPrice = new app\modules\membership_type\models\MembershipPrice();
                        $invoiceItem = $invoice_item->find()->where(["invoice_id"=>$model->invoice_id])->one();
                        $invoice = $invoice->findOne($model->invoice_id);
                        $Months = "";
                        if(!in_array($invoiceItem->invoice_item_description, $invoice_item->getArrayItemInvoice()))
                        {
                            $membership = $invoice->invoice_type_id;
                            if($membership>0 && $invoice->invoice_type == "membership")
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
                        return $Months;
                    },

            ],      
            [
              'attribute' => 'invoice_id',
              'format' => 'html',
              'header'=>Yii::t('app', 'Unit price'),
              'value' => function($model) use ($invoice_item,$invoice, $ListSetup){
                $invoice_type = $invoice->findOne($model->invoice_id)->invoice_type;
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
              'value' => function($model) use ($invoice_item, $invoice){
                $invoice_type = $invoice->findOne($model->invoice_id)->invoice_type;
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
              'value' => function($model) use ($invoice, $ListSetup){
                  $invoice_type = $invoice->findOne($model->invoice_id)->invoice_type;
                        if($invoice_type == "pos"){
                            $result = $invoice->findOne($model->invoice_id)->invoice_subtotal;
                        }else{
                            $result = $invoice->getTotalBill($model->invoice_id);
                        }
                  return $ListSetup->getDisplayPrice($result,2);
              },
//                      'footer'=>$total_billed
                    
      ],
            [
              'attribute' => 'invoice_id',
              'format' => 'html',
               'header'=>Yii::t('app', 'Incentives/Discount amt.'),
              'value' => function($model) use ($invoice){
                    $result = $invoice->getDiscount($model->invoice_id);
                    return number_format($result,0,",",".");
              },
//                      'footer'=>$total_discount_amt
                    
      ],
            [
              'attribute' => 'invoice_id',
              'format' => 'html',
              'header'=>Yii::t('app', 'Payable'),
              'value' => function($model) use ($invoice, $ListSetup){
                    $invoice = $invoice->findOne($model->invoice_id);
                    return $ListSetup->getDisplayPrice($invoice->invoice_total_last_tax,2);
              },
//                      'footer'=>$total_before_tax
                    
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
              'value' => function($model) use ($invoice){
                      $invoice = $invoice->findOne($model->invoice_id);
                      if($invoice->invoice_status == app\modules\invoice\models\invoice::INVOICE_STATUS_VOID_INVOICE)
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
echo '<a target="_blank" href="'.Yii::$app->urlManager->createUrl('/report/default/pdfpaymentreport?start_date='.$start_date.'&end_date='.$end_date.'&member='.$member_id.'&revenue_type='.$revenue_type.'&user_id='.$user_id.'&payment_method='.$payment_method).'" ><button class="btn btn-success" style = "background-color: rgb(50, 205, 139);">'.Yii::t('app','Daily payment report').'</button> </a>'      ;              
echo '<a target="_blank" href="'.Yii::$app->urlManager->createUrl('/report/default/pdfpayment?start_date='.$start_date.'&end_date='.$end_date.'&member='.$member_id.'&revenue_type='.$revenue_type.'&user_id='.$user_id.'&payment_method='.$payment_method).'" ><button class="btn btn-success" style = "background-color: rgb(50, 205, 139);">'.Yii::t('app','Print PDF').'</button> </a>'      ;              
echo '<a target="_blank" href="'.Yii::$app->urlManager->createUrl('/report/default/excelpayment?start_date='.$start_date.'&end_date='.$end_date.'&member='.$member_id.'&revenue_type='.$revenue_type.'&user_id='.$user_id.'&payment_method='.$payment_method).'" ><button class="btn btn-success" style = "background-color: rgb(50, 205, 139);">'.Yii::t('app','Export excel').'</button> </a>' ;                   
echo '</div>';


?>
    <script>
    $('#payment').on('scroll', function () {
        $("#payment > *").width($("#payment").width() + $("#payment").scrollLeft());
        });
</script>
