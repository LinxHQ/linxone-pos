<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\members\models\Members;
use kartik\date\DatePicker;
use app\models\User;
use app\models\ListSetup;
use app\modules\membership_type\models\MembershipType;
use app\modules\membership_type\models\MembershipPrice;
use app\modules\members\models\Membership;
use app\modules\invoice\models\Payment;

/* @var $this yii\web\View */
/* @var $model app\modules\invoice\models\invoice */
/* @var $form yii\widgets\ActiveForm */
$member_id=false;
$Member=new Members();
$ListSetup = new ListSetup();
$InfomembershipType = false;
$MemberShip = new Membership();
$payment = new Payment();
$createYear = date('Y');
$user = new app\models\User();

$payment_now=$payment->get_numerics($payment->getPaymentLast());

if(isset($_GET['member_id']))
    $Member = $Member->getMember($_GET['member_id']);
$MembershipPrice = new MembershipPrice();
$InfoPrice=array();
$Infomembership=array();

if(isset($_GET['membership_type']) && $_GET['membership_type']!="")
{
    $InfomembershipType= MembershipType::findOne($_GET['membership_type']);
    $InfoPrice= $MembershipPrice->getPriceByMembershipType($_GET['membership_type'],date('Y-m-d'));
    $Infomembership = $MemberShip->getMemberShip($_GET['member_id']);
}

$confirmation_code = "";
if(isset($_GET['book_id'])){
    $book_id = $_GET['book_id'];
    $book = \app\modules\booking\models\Booking::findOne($book_id);
    if($book)
        $confirmation_code = $book->confirmation_code;
}

$price=0;$membership_start_date="";$membership_end_date="";

if(count($InfoPrice) > 0)
{
    $price = $InfoPrice[0]['membership_price'];
}

if(count($Infomembership)>0)
{
    $membership_start_date = $Infomembership[0]['membership_startdate'];
    $membership_end_date = $Infomembership[0]['membership_enddate'];
}
$membership_start_date=($membership_start_date!="")?date('d/m/Y',strtotime($membership_start_date)):"";
$membership_end_date=($membership_end_date!="")?date('d/m/Y',strtotime($membership_end_date)):"";

$Method = $ListSetup->getSelectOptionList("Method");
$payment_note = $ListSetup->getSelectOptionList("payment_note");
if($InfomembershipType)
    $description = $InfomembershipType->membership_name.'<br/>'.$membership_start_date.'-'.$membership_end_date.'<br/>';
$amount = $price;
$quantity = 1;
$invoice_item_id = "";
$subTotal = $price;
$paid =0;
//echo '<pre>';
//print_r($invoiceItem);
$outstanding = $price;
if(isset($invoiceItem)){
    $invoice_item_id = $invoiceItem->invoice_item_id;
    $price = $invoiceItem->invoice_item_price;
    $quantity = $invoiceItem->invoice_item_quantity;
    $amount = $invoiceItem->invoice_item_amount;
    $description = $invoiceItem->invoice_item_description;
    $subTotal = $model->getSubtotalInvocie($model->invoice_id);
    $outstanding = $model->getInvoiceOustanding($model->invoice_id);
    $paid = $payment->getAmountByInvoice($model->invoice_id);
}
$curentcy = 0;
if($model->invoice_currency)
    $curentcy = $model->invoice_currency;
$discount_amount=0;
$tax_amount=0;
if($model->invoice_discount)
{
    $discount_arr= ListSetup::getItemByList('Discount');
    $discount_value=$model->invoice_discount;
    $discount_amount = ($amount*$discount_value)/100;
}
if($model->invoice_gst)
{
    $tax_arr= ListSetup::getItemByList('Tax');
    $tax_value=$tax_arr[$model->invoice_gst];
    $tax_amount = (($amount-$discount_amount)*$tax_value)/100;
}
?>

<h3 style="font-size: 16px; text-align: center"><?php echo Yii::t('app','Receipt'); ?> <?php echo $model->invoice_no;?> </h3> 

    <table style="float:left; width: 100%;font-size: 12px;">
        <tr>
            <td style="width: 20%;font-weight: bold"><span ><?php echo Yii::t('app','Customer'); ?>:</span></td>
            <td style="width: 38%;"> <?php echo $Member->getMemberFullName($Member->member_id);?></td>
            <td ><p style="font-weight: bold"><?php echo Yii::t('app','Date'); ?>:</p></td>
            <td  style="text-align: right;width: 20%">
                <?php
                $model->invoice_date = ($model->invoice_date) ? $model->invoice_date : date('Y-m-d');
                echo date('d/m/Y H:i:s');
                ?>
            </td>
        </tr>
        <tr>
            <td style="width: 20%;font-weight: bold;"><span ><?php echo Yii::t('app','Barcode'); ?>:</span></td><td style="width: 30%;"> <?php echo $Member->member_barcode;?></td>
            <!--<td style="width: 25%;font-weight: bold;"><span >Address:</span></td><td> <?php echo $Member->member_address;?></td>-->
            <td style="font-weight: bold"><p><?php echo Yii::t('app','Term'); ?>:</p></td>
            <td style="text-align: right"><?php echo ListSetup::getItemByList('Term')[$model->invoice_term];?></td>
        </tr>
        <tr>
            <td style="width: 20%;font-weight: bold" ></td><td style="width: 30%;"> </td>
            <td style="font-weight: bold"><p><?php echo Yii::t('app','Currency'); ?>:</p></td>
            <td style="text-align: right"><?php echo ListSetup::getItemByList('Currency')[$model->invoice_currency];?></td>
        </tr>
        <tr>
            <td style="width: 23%;font-weight: bold"><span></span></td><td style="width: 30%;"></td>
            <td style="font-weight: bold"><p><?php echo Yii::t('app','Sale person'); ?>:</p></td>
            <td style="text-align: right"><?php
            $user_arr = $user->getUser();
            echo $user_arr[$model->use_sale_id];
            ?></td>
        </tr>
        <tr>
            <td></td>
            <td style="padding-right:15px"></td>
            <td style="width: 32%;font-weight: bold" ><?php echo Yii::t('app','Created By'); ?>:</td>
            <td style="text-align: right"><?php

                echo $user_arr[$model->created_by];
            ?></td>
        </tr>
    </table>



<br/>    
<br/>    
<table class="table" style="width:100%">
<tr class="dautien invoice" style="background-color: #5bb75b;">

    <td style="color: #fff;"><?php echo Yii::t('app','Item'); ?></td>
        <td style="text-align: right;color: #fff;"><?php echo Yii::t('app','Qty'); ?></td>
        <td style="text-align: right;color: #fff;"><?php echo Yii::t('app','Price'); ?></td>
        <td style="text-align: right;color: #fff;" ><?php echo Yii::t('app','Total'); ?></td>
    </tr>
    <tr>

        <td id="invoice_item_description">
            <?php echo $description; ?>
        </td>
        <td style="text-align: right;" ><?php echo number_format($quantity,0,",","."); ?></td>
        <td style="text-align: right;" ><?php echo number_format($price,0,",",".");?></td>
        <td style="text-align: right;" > <?php echo number_format($amount,0,",",".");?> </span></td>
    </tr>
</table>

    <table class="table total1" style="width:100%;text-align:right;float:right;">
        <tr>
            <td ><?php echo Yii::t('app','Sub Total'); ?>:</td>
            <td> <span id="sub_total"><?php echo number_format($amount,0,",",".");?><?php echo " ".ListSetup::getItemByList('Currency')[$curentcy]; ?></span></td>
        </tr>
        <tr>
            <td><?php echo Yii::t('app','Discount'); ?></td>
            <td>                
                <span><?php echo number_format($discount_amount,0,",",".");?></span>
                <span>VND</span>
                
            </td>
        </tr>
        <tr>
            <td><?php echo Yii::t('app','TAX'); ?></td>
            <td><?php echo number_format($tax_amount,0,",",".");?> <span> VND</span> </td>
        </tr>
        <tr>
            <td><?php echo Yii::t('app','Total'); ?></td>
            <td> <span id="total"><?php echo number_format($subTotal,0,",",".");?><?php echo " ".ListSetup::getItemByList('Currency')[$curentcy]; ?></span></td>
        </tr>
        <tr>
            <td><?php echo Yii::t('app','Paid'); ?></td>
            <td> <span id="paid"><?php echo number_format($paid,0,",","."); ?><?php echo " ".ListSetup::getItemByList('Currency')[$curentcy]; ?></span></td>
        </tr>
        <tr>
            <td ><?php echo Yii::t('app','Outstanding'); ?></td>
            <td> <span id="oustanding"><?php echo number_format($outstanding,0,",",".");?><?php echo " ".ListSetup::getItemByList('Currency')[$curentcy]; ?></span></td>
        </tr>
    </table>

<br/><br/>
<table class="table" id="table_payment" style="text-align: left;width: 100%;font-size: 12px" cellspacing="4">
    <tr class="dautien invoice" style="background-color: #5bb75b;">

        <td style="color: #fff;text-align: center;width:20%" width="20%"><?php echo Yii::t('app','Payment No'); ?></td>
        <td style="color: #fff; text-align: center"><?php echo Yii::t('app','Payment Date'); ?></td>
        <td style="color: #fff; text-align: center"><?php echo Yii::t('app','Method'); ?></td>
        <td style="text-align: right;color: #fff;"><?php echo Yii::t('app','Amount'); ?></td>
        <td style="color: #fff;text-align: center" width="20%"><?php echo Yii::t('app','Note'); ?></td>
    </tr>
    <?php 
    if(isset($invoicePayment)){
        foreach($invoicePayment as $payment_item) { ?>

        <tr>
            <td style="text-align: center" >
                <?php echo $payment_item->payment_no; ?>
            </td>
            <td style="text-align: center" ><?php echo date('d/m/Y H:i:s',strtotime($payment_item->payment_date)); ?>
            <td style="text-align: center" >
                <?php echo $ListSetup->getItemByList('Method')[$payment_item->payment_method]; ?>
            </td>
            <td style="text-align: right"><?php echo ListSetup::getItemByList('Currency')[$curentcy]; ?> <?php echo number_format($payment_item->payment_amount,0,",","."); ?></td>
            <td style="text-align: center" ><?php echo  (ListSetup::getItemByList('payment_note')[$payment_item->payment_note])?ListSetup::getItemByList('payment_note')[$payment_item->payment_note]:""; ?></td>
        </tr>
    <?php }} ?>

</table>
            
      


