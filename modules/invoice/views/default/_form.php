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
use app\modules\invoice\models\InvoiceItem;
use app\modules\revenue_type\models\RevenueItem;
$revenue = new RevenueItem();
$package_arr = $revenue->getRevenueItemByEntry(2,'array','index');

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
$uer_arr = $user->getUser();
$payment_now=$payment->get_numerics($payment->getPaymentLast());
echo '<input hidden="true" id="next_number_payment" value="'.$payment->FormatPaymentNo($payment_now[0]).'" />';
echo '<input hidden="true" id="next_number_payment_year" value="R-'.$payment->FormatPaymentNo($payment_now[0]).'" />';
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

if(count($InfoPrice) > 0 && $InfoPrice[0]['membership_price'] >0)
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
$date_apply = "";
if($InfomembershipType){
    $description = $InfomembershipType->membership_name.'<br/>'.$membership_start_date.'-'.$membership_end_date.'<br/>';
    $date_apply = $MembershipPrice->getDateMemberTypeApplyPrice($InfomembershipType->membership_type_id, $model->invoice_date);
}
$amount = $price;
$quantity = 1;
$invoice_item_id = "";
$subTotal = $price;
$paid =0;
$invoiceItemManage = new InvoiceItem();

$outstanding = $price;
//print_r($model);
$description = "";
if(isset($invoiceItem)){
    $invoice_item_id = $invoiceItem->invoice_item_id;
    $price = $invoiceItem->invoice_item_price;
    $quantity = $invoiceItem->invoice_item_quantity;
    $amount = $invoiceItem->invoice_item_amount;
    $invoiceItemManage->getArrayItemInvoice();
    if(!in_array(trim($invoiceItem->invoice_item_description), $invoiceItemManage->getArrayItemInvoice()))
        $description = $invoiceItem->invoice_item_description.'<br>'.$date_apply;
    else
        $description = $invoiceItem->invoice_item_description;
    $subTotal = $model->getSubtotalInvocie($model->invoice_id);
    
    $outstanding = $model->getInvoiceOustanding($model->invoice_id);
    $paid = $payment->getAmountByInvoice($model->invoice_id);
}
elseif(isset ($_GET['membership_type']))
{
    $description=$invoiceItemManage->setListItemInvoice($_GET['membership_type']);
}
elseif(isset ($_GET['training_id']) && $_GET['training_id']>0){
    $ModelMemberTrainings= \app\modules\training\models\MemberTrainings::findOne($_GET['training_id']);
        $description = (isset($package_arr[$ModelMemberTrainings->package_id]) ? $package_arr[$ModelMemberTrainings->package_id] : "") .'<br/>';
}
$curentcy = 0;
if(!$model->invoice_discount)
    $model->invoice_discount=0;
if($model->invoice_currency)
    $curentcy = $model->invoice_currency;
?>
<?php $form = ActiveForm::begin(['options' => ['onSubmit'=>"return berfore_submit_form(); "]]); ?>
<div class="park-header">
            <span class="glyphicon glyphicon-calendar" aria-hidden="true"></span>
            <h2 style="font-size: 60px;"><?php echo Yii::t('app','Invoice'); ?> </h2> </div>
            <div class="custommer invoice col-lg-6 col-md-6 col-md-12 col-xs-12">
                <table style="float:left; width: 100%">
                    <tr>
                        <td style="width: 35%"><h5><span><?php echo Yii::t('app','Customer'); ?>:</span></h5></td><td><h5> <?php echo $Member->getMemberFullName($Member->member_id);?></h5></td>
                    </tr>
                    <tr>
                        <td><h5><span><?php echo Yii::t('app','Address'); ?>:</span></h5></td><td><h5> <?php echo $Member->member_address;?></h5></td>
                    </tr>
                    <tr>
                        <td><h5><span><?php echo Yii::t('app','Mobile'); ?>:</span></h5></td><td><h5> <?php echo $Member->member_mobile;?></h5></td>
                    </tr>
                    <tr>
                        <td><h5><span><?php echo Yii::t('app','Email'); ?>:</span></h5></td><td><h5> <?php echo $Member->member_email;?></h5></td>
                    </tr>
                    <tr>
                        <td><h5><span><?php echo Yii::t('app','VAT invoice date'); ?>:</span></h5></td>
                        <td>
                            <?php        
                                $model->invoice_vat_date = (($model->invoice_vat_date) ? date('Y-m-d',strtotime($model->invoice_vat_date)) : date('Y-m-d'));
                                echo $form->field($model, 'invoice_vat_date')->widget(DatePicker::classname(), [
                                    'options' => ['value' =>  $model->invoice_vat_date],
                                    'pluginOptions' => [
                                        'autoclose'=>true,
                                        'format' => 'yyyy-mm-dd',
                                        'startDate'=> ""
                                    ],
                                    'type' => DatePicker::TYPE_COMPONENT_APPEND,
                                ])->label(false);     
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td><h5><span><?php echo Yii::t('app','VAT invoice no'); ?>:</span></h5></td>
                        <td>
                            <?= $form->field($model, 'invoice_vat_no')->textInput(['value'=>$model->invoice_vat_no])->label(false); ?>
                        </td>
                    </tr>
                    <tr>
                        <td><h5><span><?php echo Yii::t('app','VAT invoice amount'); ?>:</span></h5></td>
                        <td>
                            <?= $form->field($model, 'invoice_vat_amount')->textInput(['value'=>$model->invoice_vat_amount])->label(false); ?>
                        </td>
                    </tr>
                    <tr>
                        <td><h5><span><?php echo Yii::t('app','VAT status'); ?>:</span></h5></td>
                        <td>
                             <?= $form->field($model, 'invoice_vat_status')->dropDownList(ListSetup::getItemByList('vat_status'))->label(false) ?>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="custommer2 invoice col-lg-6 col-md-6 col-md-12 col-xs-12">
                <table style="float:right; width: 100%;">
                    <tr>
                        <td><p style="margin-right: 10px"><?php echo Yii::t('app','Invoice number'); ?></p></td>
                        <td style="text-align: right;"><p><?php echo ($model->invoice_no) ? $model->invoice_no : "";?></p></td>
                    </tr>
                    <?php if($confirmation_code!="") { ?>
                    <tr>
                        <td style="width: 65%"><p style="margin-right: 10px"><?php echo Yii::t('app','Confirmation code'); ?>:<p style="margin-right: 10px"></td>
                        <td><p><?php echo $confirmation_code;?></p></td>
                    </tr>
                    <?php } ?>
                    <tr>
                        <td ><p style="margin-right: 10px"><?php echo Yii::t('app','Date'); ?></p></td>
                        <td >
                            
                            <?php
                            $model->invoice_date = ($model->invoice_date) ? $model->invoice_date : date('Y-m-d h:i:s');
                           
                            ?>
                            <?= $form->field($model, 'invoice_date')->textInput(['value'=>$model->invoice_date,'readonly'=>true])->label(false); ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-right:10px"><p><?php echo Yii::t('app','Term'); ?></p></td>
                        <td><?= $form->field($model, 'invoice_term')->dropDownList(ListSetup::getItemByList('Term'))->label(false) ?></td>
                    </tr>
                    <tr>
                        <td style="padding-right:10px"><p><?php echo Yii::t('app','Currency'); ?></p></td>
                        <td>
                            <?= $form->field($model, 'invoice_currency')->dropDownList(ListSetup::getItemByList('Currency'),['disabled'=>'true'])->label(false) ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-right:10px"><p><?php echo Yii::t('app','Saler'); ?>:</p></td>
                        <td><?= $form->field($model, 'use_sale_id')->dropDownList($user->getUser())->label(false) ?></td>
                    </tr>
                    <tr>
                        <td style="padding-right:10px"><p><?php echo Yii::t('app','Created By'); ?>:</p></td>
                        <td><?php 
                        
                        if($model->created_by)
                            echo $uer_arr[$model->created_by]; 
                        else {
                                echo Yii::$app->user->identity->username;
                        }    
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-right:10px"><p><?php echo Yii::t('app','Status'); ?></p></td>
                        <td><?php 
                        echo $model->invoice_status;
                            ?>
                        </td>
                    </tr>
                </table>

            </div>
            <table class="table">
                <tr class="dautien invoice">
                    <td>#</td>
                    <td><?php echo Yii::t('app','Item'); ?></td>
                    <td><?php echo Yii::t('app','Quantity'); ?></td>
                    <td><?php echo Yii::t('app','Price'); ?></td>
                    <td><?php echo Yii::t('app','Total'); ?></td>
                </tr>
                <tr>
                    <td>1
                        <input type="hidden" name="invoice_item_id" value="<?php echo $invoice_item_id; ?>" />
                        <?php $invoice_type="membership";
                            if(isset($_GET['book_id'])){
                                $invoice_type="booking";
                            }
                            if(isset($_GET['training_id']) && $_GET['training_id']>0){
                                $invoice_type="trainer";
                            }
                        ?>
                        <?php echo '<input type="hidden" name="invoice_type" value='.$invoice_type.' id="invoice_type"/>'; ?>
                    </td>
                    
                    <td id="invoice_item_description">
                        <?php echo $description; ?>
                    </td>
                    <td ><input type="text" id="quantity" onChange ="change_amount();return false;" value="<?php echo $quantity; ?>" size="5" placeholder="" style="height: 30px; font-size: 12px;padding: 5px;width: 100%" /></td>
                    <!-- <td ><input type="text" id="price" onChange ="change_amount();return false;" value="<?php echo $price;?>" size="5" placeholder="" style="height: 30px; font-size: 12px;padding: 5px;width: 100%" /></td> -->
                    <td ><input type="text" id="price" onChange ="change_amount();return false;" value="<?php echo number_format($price,0,".",".");?>" size="5" placeholder="" style="height: 30px; font-size: 12px;padding: 5px;width: 100%" /></td>
                    <td style="text-align: right;"><span id="amount" size="5" placeholder="" style="height: 30px; font-size: 12px;padding: 5px;"> <?php echo ListSetup::getItemByList('Currency')[$curentcy]; ?> <?php echo number_format($amount,0,".",".");?> </span></td>
                </tr>
            </table>
            <div class="total">
                <table class="table total1" style="width:71%;text-align:right;float:right;">
                    <tr hidden="">
                        <td><?php echo Yii::t('app','Sub Total'); ?>:</td>
                        <td><?php echo ListSetup::getItemByList('Currency')[$curentcy]; ?> <span id="sub_total"><?php echo number_format($amount,0);?></span></td>
                    </tr>
                    <tr>
                        <td><?php echo Yii::t('app','Discount'); ?></td>
                        <td>
                            <span><?= $form->field($model, 'invoice_discount')->textInput(['onChange'=>'change_amount();','style'=>'width:70px;float:left;margin-top:-16px;margin-left:71px'])->label(false) ?></span>
                            <span>%</span>
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo Yii::t('app','TAX'); ?></td>
                        <td><?= $form->field($model, 'invoice_gst')->dropDownList(ListSetup::getItemByList('Tax'),['onChange'=>'change_amount();','style'=>'width:70px;float:left;margin-top:-16px;margin-left:71px;'])->label(false) ?><span>%</span></td>
                    </tr>
                    <tr>
                        <td><?php echo Yii::t('app','Total'); ?></td>
                        <td><?php echo ListSetup::getItemByList('Currency')[$curentcy]; ?> <span id="total"><?php echo number_format($subTotal,0,',','.');?></span></td>
                    </tr>
                    <tr>
                        <td><?php echo Yii::t('app','Paid'); ?></td>
                        <td>
                            <?php echo ListSetup::getItemByList('Currency')[$curentcy]; ?> <span id="paid"><?php echo number_format($paid,0,",","."); ?></span>
                            <input hidden="" type="text" value="<?php echo $paid;?>" id="paid_value"/>
                        </td>
                    </tr>
                    <tr>
                        <td ><?php echo Yii::t('app','Outstanding'); ?></td>
                        <td>
                            <?php echo ListSetup::getItemByList('Currency')[$curentcy]; ?> <span id="oustanding"><?php echo $outstanding;?></span>
                            <input hidden="" type="text" value="<?php echo number_format($outstanding,0,",",".") ; ?>" id="oustanding_value" />
                        </td>
                    </tr>
                </table>

            </div>

<!-- ####### LIST PAYMENT ######## -->
<table class="table" id="table_payment" style="text-align: left;" cellspacing="4">
                <tr class="dautien invoice">
                    
                    <td width="15%" ><?php echo Yii::t('app','Payment No'); ?></td>
                    <td width="25%" ><?php echo Yii::t('app','Payment Date'); ?></td>
                    <td width="15%" ><?php echo Yii::t('app','Method'); ?></td>
                    <td style="text-align: left;width:15%"><?php echo Yii::t('app','Reference'); ?></td>
                    <td style="text-align: right;width:20%"><?php echo Yii::t('app','Amount'); ?></td>
                    <td width="20%"><?php echo Yii::t('app','Note'); ?></td>
                    <td width="15%"><?php echo Yii::t('app','Created by'); ?></td>
                    <td width="15%"></td>
                </tr>
                <?php 
                if(isset($invoicePayment)){
                    foreach($invoicePayment as $payment_item) { 
                            $class_void = "";
                            $void_payment = '<button type="button" onclick="void_receipt('.$payment_item->payment_id.',1,'.$model->invoice_id.');" id="button_checkin" class="btn btn-danger">'.Yii::t('app','Void Receipt').'</button>';
                            if($payment_item->payment_void == 1){
                                $class_void = Yii::t('app','Void');
                                $void_payment = Yii::t('app','Void Receipt');
                            }
                        ?>
                
                <tr class="<?php echo $class_void; ?>">
                        <td>
                            <?php echo $payment_item->payment_no; ?>
                        </td>
                        <td><?php echo date('d/m/Y H:i:s',  strtotime($payment_item->payment_date)); ?>
                        <td>
                            <?php echo ListSetup::getItemByList('Method')[$payment_item->payment_method]; ?>
                        </td>
                        <td>
                            <?php echo $payment_item->reference; ?>
                        </td>
                        <td style="text-align: right"><?php echo ListSetup::getItemByList('Currency')[$curentcy]; ?> <?php echo number_format($payment_item->payment_amount,0,".","."); ?></td>
                        <td><?php echo  (ListSetup::getItemByList('payment_note')[$payment_item->payment_note])?ListSetup::getItemByList('payment_note')[$payment_item->payment_note]:""; ?></td>
                        <td><?php 
                        if($payment_item->created_by)
                            echo $uer_arr[$payment_item->created_by]; 
                        else {
                                echo Yii::$app->user->identity->username;
                        }    
                        ?></td>
                        <td>
                            <?php echo $void_payment; ?>
                        </td>
                    </tr>
                <?php }} ?>

            </table>
<div id="form_add_payment"></div>
            <?php if($model->invoice_status!=\app\modules\invoice\models\invoice::INVOICE_STATUS_VOID_INVOICE){ ?>
            <a style="float:left" href="#" onclick="add_payment();return false;"><?php echo Yii::t('app','New Payment'); ?></a><br/>
            <?php } ?>
<!------  END LIST PAYMENT -->
            
            <div >
<!--                <a href="../html/member2.html"><button class="btn btn-success">Save</button></a>
                <a href="../html/member2.html"><button name="written_off" class="btn btn-success">Written-off</button></a>
                <button class="btn btn-success">Print</button></div>-->
            <div class="form-group">
                    <?php if($model->invoice_status!=\app\modules\invoice\models\invoice::INVOICE_STATUS_VOID_INVOICE){ ?>
                        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Paid') : Yii::t('app', 'Paid'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-success','name'=>"create"]) ?>
                        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Void invoice') : Yii::t('app', 'Void invoice'), ['class' => $model->isNewRecord ? 'btn btn-danger' : 'btn btn-danger','name'=>"void_invoice"]) ?>
                    <?php } ?>
                <?php 
                              $book_id=0;
                if(isset($_GET['book_id']))
                    $book_id=$_GET['book_id'];
                $url_pdf_a4 = '#';$url_pdf="#";
                if($model->invoice_type=="booking")
                    $url_pdf= YII::$app->urlManager->createUrl("/invoice/default/printinvoice?id=". $model->invoice_id."&member_id=".$_GET['member_id']."&book_id=".$_GET['book_id']);
                elseif(isset($_GET['membership_type'])){
                    $url_pdf= YII::$app->urlManager->createUrl("/invoice/default/printinvoice?id=". $model->invoice_id."&membership_type=".$_GET['membership_type']."&membership_id=".$_GET['membership_id']."&member_id=".$_GET['member_id']."&book_id=0");
                    $url_pdf_a4= YII::$app->urlManager->createUrl("/invoice/default/printinvoice_a4?id=". $model->invoice_id."&membership_type=".$_GET['membership_type']."&membership_id=".$_GET['membership_id']."&member_id=".$_GET['member_id']."&book_id=0");
                }

                $url_contract= YII::$app->urlManager->createUrl("/invoice/default/printcontract?id=". $model->invoice_id."&member_id=".$model->member_id."&book_id=".$book_id);
                ?>
                    <button type="button" onclick="popcheckin();" id="button_checkin" class="btn btn-success"><?php echo Yii::t('app', 'Print Invoice/Receipt');?></button>
                    <?php if($model->invoice_type == "membership" && $model->invoice_status == \app\modules\invoice\models\invoice::INVOICE_STATUS_PAID)
                    {?>
                    <button type="button" onclick="popcheckin();" id="button_checkin" class="btn btn-success"><?php echo Yii::t('app', 'Print Contract');?></button>
                    <?php }?>      
            </div>
        </div>
<!-- MODAL Print Invoid -->
        <div id="bs-model-checkin" class="modal fade bs-example-modal-lg popupCheckin" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
                <div class="modal-dialog modal-lg" role="document">
                    <div id="modal-content-checkin" class="modal-content">
                        <br/><a target="_blank" style="margin-top:20px;" href='<?php echo $url_pdf_a4;?>' ><input type="button" class="btn btn-success" value="<?php echo Yii::t('app','Invoice'); ?>"> </a>
                        <a target="_blank" style="margin-top:20px;" href='<?php echo $url_pdf;?>' ><input type="button" class="btn btn-success" value="<?php echo Yii::t('app','Receipt'); ?>"> </a>
                 
                    </div>
                </div>
            </div>
<!-- END MODAL Print Invoid -->
<!-- MODAL Print Contract -->
            <div id="bs-model-checkin" class="modal fade bs-example-modal-lg popupCheckin" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
                <div class="modal-dialog modal-lg" role="document">
                    <div id="modal-content-checkin" class="modal-content">
                        <br/><a target="_blank" style="margin-top:20px;" href='<?php echo $url_contract.'&template=1';?>' ><input type="button" class="btn btn-success" value="<?php echo Yii::t('app','Agreement English'); ?>"> </a>
                        <a target="_blank" style="margin-top:20px;" href='<?php echo $url_contract;?>' ><input type="button" class="btn btn-success" value="<?php echo Yii::t('app','Agreement Vietnamese'); ?>"> </a>
                 
                    </div>
                </div>
            </div>
<!-- END MODAL Print Contract -->

    

<div hidden="true">

    <input id='date_now' value="<?php echo date('Y-m-d h:i:s');?>" />


    <?php ActiveForm::end(); ?>

<script>
        var check=0;
        var count = 0;
        var next_number_payment;
        
        (function($) {
            $('#search_value').keypress(function(event){
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                  search_membership_type();
                }
            });

        })(jQuery);
        function numberWithCommas(x) {
          x=String(x).toString();
          var afterPoint = '';
          if(x.indexOf('.') > 0)
             afterPoint = x.substring(x.indexOf('.'),x.length);
          x = Math.floor(x);
          x=x.toString();
          var lastThree = x.substring(x.length-3);
          var otherNumbers = x.substring(0,x.length-3);
          if(otherNumbers != '')
              lastThree = '.' + lastThree;
          return otherNumbers.replace(/\B(?=(\d{3})+(?!\d))/g, ".") + lastThree + afterPoint;
        }
        function change_amount()
        {
            
            var quantity = $('#quantity').val();
            var price = $('#price').val();
            check=0;
            price = price.split('.').join('');
            if(quantity=="")
            {
                alert('Please Enter Quantity!');
                check=1;
                return;
            }
            if(price=="")
            {
                alert('Please Enter Price!');
                check=1;
                return;
            }

            var amount = parseFloat(quantity) * parseFloat(price);
            var discount = $('#invoice-invoice_discount').val();
            discount = discount.split('.').join('');
            
            var discount_value = discount;
            var tax_value = $("#invoice-invoice_gst option:selected").text();
            tax_value = tax_value.split('.').join('');
            
            $('#sub_total').html(amount);
            var sub_total = $('#sub_total').text();
            sub_total = sub_total.split('.').join('');
            var discount_amount = (parseFloat(sub_total)*parseFloat(discount_value))/100;
            discount_amount=discount_amount.toFixed(0);
            var total=parseFloat(sub_total)-parseFloat(discount_amount);
            
            var gst_amount = (parseFloat(total)*parseFloat(tax_value))/100;
            gst_amount = gst_amount.toFixed(0);
            
            total = parseFloat(sub_total) - parseFloat(discount_amount)  + parseFloat(gst_amount);
            var paid = $('#paid_value').val();
            paid = paid.split('.').join('');
            var oustanding = parseFloat(total)-parseFloat(paid);
           
            $('#amount').html(numberWithCommas(amount));
            
            $('#total').html(number_format(total,0,",","."));
            $('#oustanding').html(oustanding.toFixed(0));
            var payment_input_amount=0;
            var payment;
            //all payment amount input
            $("input[type='text'][name='payment_amount[]']").each(function(){
                payment = $(this).val();
                payment = payment.split('.').join('');
//                if(isNaN(payment))
//                    alert('Payment amount must be number');
//                else
                payment=parseFloat(payment);
                payment_input_amount+=payment;
            });
//                        alert(payment_input_amount);
//                        alert(oustanding);
            oustanding = parseFloat(oustanding)-parseFloat(payment_input_amount);
            $('#oustanding_value').val(oustanding.toFixed(0));
            $('#paid').html(number_format(payment_input_amount,0,",","."));
            $('#oustanding').html(number_format(oustanding,0,",","."));
            
        }
        function popcheckin(){
            
            $('.modal-content').css({'min-height':'70px'});
            $('#bs-model-checkin').modal('show'); 
            
        }
        function add_payment()
        {
            var date_now=$('#date_now').val();
            var next_number_payment=$('#next_number_payment').val();
            var now_oustanding = $('#oustanding_value').val();
            
            $.ajax({
                url: "<?php echo Yii::$app->urlManager->createUrl('/invoice/default/getpaymentnextnumber');?>",
                data: {next_number_payment:next_number_payment},
                success: function (data) {
                    if (data !== "") {
                        $('#next_number_payment').val(data);
                    } 
                }
            });
            
            $.ajax({
                type:'POST',
                url: "<?php echo Yii::$app->urlManager->createUrl('/invoice/default/add_payment');?>",
                data: {next_number_payment:next_number_payment,now_oustanding:now_oustanding},
                success: function (data) {
                    $('#form_add_payment').before(data);
                    change_amount();
                }
            });
//            var html ="<tr>\n\
//                <td ><input name='payment_no[]' value='"+next_number_payment+"' readonly='true'>\n\
//                <input type='hidden' name='payment_id[]' value='0' readonly='true'></td>\n\
//                <td ><input name='payment_date[]' value='"+date_now+"' readonly='true' /></td>\n\
//                <td ><span name='payment_method[]'><?php echo $Method ?></span></td>\n\
//                <td ><input type='text' name='reference[]' value='' ></td>\n\
//                <td  ><input style='width: 100%' onChange=change_amount(); name='payment_amount[]' type='text' value="+now_oustanding+" size='5' placeholder='0.00' style='height: 30px; font-size: 12px;padding: 5px;' /></td>\n\
//                <td ><span name='payment_note[]'><?php echo $payment_note ?></td>\n\
//                <td ><span name='created_by[]'><?php echo Yii::$app->user->identity->username; ?></span></td>\n\
//                </tr>";
//            $('#table_payment tr:last').after(html);
            change_amount();
           
//            $('#table_payment').add(html);
        }
        
        function berfore_submit_form()
        {
            
            var invoice_item;
            var invoice_item_description = $('#invoice_item_description').html();
            var quantity = $('#quantity').val();
            var price = $('#price').val();
            var amount = $('#amount').html();
            
            if(check == 1)
                return false;
            else
            {
                if ($('#invoice_item_value').length) {
                    var invoice_item_value = $('#invoice_item_value').val();
                        $('<input />').attr('type', 'hidden')
                            .attr('name', "invoice_item_description")
                            .attr('value', invoice_item_value)
                            .appendTo('form');
                }
                else
                {
                    $('<input />').attr('type', 'hidden')
                        .attr('name', "invoice_item_description")
                        .attr('value', invoice_item_description)
                        .appendTo('form');
                }
                $('<input />').attr('type', 'hidden')
                    .attr('name', "invoice_item_quantity")
                    .attr('value', quantity)
                    .appendTo('form');

                $('<input />').attr('type', 'hidden')
                    .attr('name', "invoice_item_price")
                    .attr('value', price)
                    .appendTo('form');

                $('<input />').attr('type', 'hidden')
                    .attr('name', "invoice_item_amount")
                    .attr('value', amount)
                    .appendTo('form');

                $('<input />').attr('type', 'hidden')
                    .attr('name', "invoice_type")
                    .attr('value', '<?php echo $invoice_type?>')
                    .appendTo('form');

                return true;
            }
                
        }
        function void_receipt(payment_id,status,invoice_id){
            $.ajax({
                'type':'POST',
                'url':'<?php echo YII::$app->urlManager->createUrl('/invoice/default/void_payment'); ?>',
                'beforeSend':function(){
                    if(confirm("Are you sure to void this receipt?"))
                        return true;
                    return false;
                },
                'data':{'payment_id':payment_id,'status':status,invoice_id:invoice_id},
                success:function(data){
                    if(data=='success')
                        location.reload();
                    else
                        alert('void receipt error');
                }
            });
        }
</script>