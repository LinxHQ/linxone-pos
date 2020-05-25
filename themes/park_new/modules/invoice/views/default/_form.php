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
use kartik\select2\Select2;

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
$config = app\models\Config::find()->one();
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
print_r($model);
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
    $Member = $Member->getMember($model->member_id);
   
}
elseif(isset ($_GET['membership_type']))
{
    $description=$invoiceItemManage->setListItemInvoice($_GET['membership_type']);
//    if(!$Member->isMemberShip($_GET['member_id']))
//        $description = InvoiceItem::INVOICE_ITEM_GUEST;
}
elseif(isset ($_GET['training_id']) && $_GET['training_id']>0){
    $description="";
    $ModelMemberTrainings= \app\modules\training\models\MemberTrainings::findOne($_GET['training_id']);
    $training_package = RevenueItem::find()->where(['revenue_item_id'=>$ModelMemberTrainings->package_id])->one();
    if($training_package){
        $description = $training_package->revenue_item_name.'<br/>';
        $price = $training_package->revenue_item_price;
    }
}
$is_addinvoice_trainer = 0;
if(isset($_GET['training_id']) && $_GET['training_id']>0 && !isset($model->invoice_id))
    $is_addinvoice_trainer = 1;
//if(isset($_GET['membership_type']) && $_GET['membership_id']>0 && !isset($model->invoice_id))
//    $is_addinvoice_trainer = 1;

$curentcy = 0;
if(!$model->invoice_discount)
    $model->invoice_discount=0;
if($model->invoice_currency)
    $curentcy = $model->invoice_currency;

$currency = 0;
if($config)
	$currency = $config->currency;

//if($model->invoice_type=='trainer'){
//    $training_id = $model->invoice_type_id;
//    $ModelMemberTrainings= \app\modules\training\models\MemberTrainings::findOne($training_id);
//    $ModelMemberTrainer= app\modules\training\models\MemberTrainers::find()->where(["member_training_id"=>$training_id])->one();
//}


?>
 <?php $form = ActiveForm::begin(['options' => ['onSubmit'=>"return berfore_submit_form(); ",'id'=>'form-invoice']]); ?>
<div id="members-index" class="parkclub-wrapper parkclub-wrapper-search ">
    <div class="parkclub-rectangle parkclub-shadow">
       <div class="parkclub-invoice">
            <div class="parkclub-newm-left-title">
                <div class="parkclub-header-left">
                    <a href="<?php echo Yii::$app->urlManager->createUrl('members/default/update?id='.$Member->member_id); ?>"><i class="glyphicon glyphicon-circle-arrow-left"></i></a>
                    <?php echo Yii::t('app', 'INVOICE');?>
                </div>
            </div>
           <br>
            <div class="custommer col-lg-6 col-md-6 col-md-12 col-xs-12">
                <table class="view-info" style="float:left; width: 100%">
                    <tr>
                        <td style="width: 35%"><?php echo Yii::t('app', 'Customer');?>:</td><td><h5> 
                                <a href="<?php echo Yii::$app->urlManager->createUrl('members/default/update?id='.$Member->member_id); ?>"><?php echo $Member->getMemberFullName($Member->member_id);?></a>
                            </h5></td>
                    </tr>
                    <tr>
                        <td><?php echo Yii::t('app', 'Address');?>:</td><td><h5> <?php echo $Member->getMemberFullAddress($Member->member_id);?></h5></td>
                    </tr>
                    <tr>
                        <td><?php echo Yii::t('app', 'Mobile');?>:</td><td><h5> <?php echo $Member->member_mobile;?></h5></td>
                    </tr>
                    <tr>
                        <td><?php echo Yii::t('app', 'Email');?>:</td><td><h5><?php echo $Member->member_email;?></h5></td>
                    </tr>
                    <tr>
                        <td><?php echo Yii::t('app', 'VAT invoice date');?>:</td>
                        <td>
                            <?php        
                                $model->invoice_vat_date = (($model->invoice_vat_date) ? date('Y-m-d',strtotime($model->invoice_vat_date)) : "");
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
                        <td><?php echo Yii::t('app', 'VAT invoice no');?>:</td>
                        <td>
                            <?= $form->field($model, 'invoice_vat_no')->textInput(['value'=>$model->invoice_vat_no])->label(false); ?>
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo Yii::t('app', 'VAT invoice amount');?>:</td>
                        <td>
                            <?= $form->field($model, 'invoice_vat_amount')->textInput(['value'=>$model->invoice_vat_amount])->label(false); ?>
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo Yii::t('app', 'VAT status');?>:</td>
                        <td>
                            <?= $form->field($model, 'invoice_vat_status')->dropDownList(ListSetup::getItemByList('vat_status'))->label(false) ?>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="custommer2 col-lg-6 col-md-6 col-md-12 col-xs-12">
                <table class="view-info" style="float:right; width: 100%;" cellspacing="10">
                    <tr>
                        <td ><?php echo Yii::t('app', 'Invoice number');?></td>
                        <td style="text-align: right;"><p><?php echo ($model->invoice_no) ? $model->invoice_no : "";?></p></td>
                    </tr>
                    <?php if($confirmation_code!="") { ?>
                    <tr>
                        <td ><?php echo Yii::t('app', 'Confirmation code');?>:</td>
                        <td  style="width: 65%"><b><?php echo $confirmation_code;?></b></td>
                    </tr>
                    <?php } ?>
                    <tr>
                        <td ><?php echo Yii::t('app', 'Date');?></td>
                        <td >
                            <?php
                            $model->invoice_date = ($model->invoice_date) ? $model->invoice_date : date('Y-m-d h:i:s');
                           
                            ?>
                            <?= $form->field($model, 'invoice_date')->textInput(['value'=>$model->invoice_date,'readonly'=>true])->label(false); ?>
                        </td>
                    </tr>
                    <tr>
                        <td ><?php echo Yii::t('app', 'Term');?></td>
                        <td><?= $form->field($model, 'invoice_term')->dropDownList(ListSetup::getItemByList('Term'))->label(false) ?></td>
                    </tr>
                    <tr>
                        <td ><?php echo Yii::t('app', 'Currency');?></td>
                        <td>
                            <?= $form->field($model, 'invoice_currency')->dropDownList(ListSetup::getItemByList('Currency'),['options' => [$currency => ['Selected'=>'selected']],'disabled'=>'true'])->label(false) ?>
                        </td>
                    </tr>
                    <tr>
                        <td ><?php echo Yii::t('app', 'Sale person');?>:</td>
                        <td><?php
                        if(!isset($model->use_sale_id))
                            $model->use_sale_id = Yii::$app->user->id;
                        echo $form->field($model, 'use_sale_id')->dropDownList($user->getUser())->label(false) ?></td>
                    </tr>
                    <tr>
                        <td ><?php echo Yii::t('app', 'Created By');?></td>
                        <td><b><?php 
                        
                        if($model->created_by){
                            $user_created = User::findOne($model->created_by);
                            if($user_created)
                                echo $user_created->getFullName();
                        }
                        else {
                                echo Yii::$app->user->identity->username;
                        }    
                        ?></b>
                        </td>
                    </tr>
                    <tr>
                        <td ><?php echo Yii::t('app', 'Status');?></td>
                        <td><b><?php 
                        echo $model->invoice_status;
                        ?></b>
                        </td>
                    </tr>
                </table>

            </div>
           <br><br>
            <table class="table">
                <tr class="dautien invoice">
                    <td width="2%">#</td>
                    <td width="50%"><?php echo Yii::t('app', 'Item');?></td>
                    <td width="15%"><?php echo Yii::t('app', 'Quantity');?></td>
                    <td width="20%"><?php echo Yii::t('app', 'Price');?></td>
                    <td align="right"><?php echo Yii::t('app', 'Total');?></td>
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
                    <td ><input type="text" id="quantity" onChange ="change_amount();return false;" value="<?php echo $quantity; ?>" size="5" placeholder=""/></td>
                    <td ><input type="text" id="price" onChange ="change_amount();return false;" value="<?php echo ListSetup::getDisplayPrice($price);?>" size="5"/></td>
                    <td style="text-align: right;"><span id="amount" size="5" placeholder=""> <?php echo (isset(ListSetup::getItemByList('Currency')[$currency]) ? ListSetup::getItemByList('Currency')[$currency] : ""); ?> <?php echo number_format($amount,0,".",".");?> </span></td>
                </tr>
            </table>
            <div class="total">
                <table class="table total1" style="width:71%;text-align:right;float:right;">
                    <tr>
                        <td><?php echo Yii::t('app', 'Sub Total');?>:</td>
                        <td><?php echo (isset(ListSetup::getItemByList('Currency')[$currency]) ? ListSetup::getItemByList('Currency')[$currency] : ""); ?> <span id="sub_total"><?php echo ListSetup::getDisplayPrice($model->invoice_subtotal);?></span></td>
                    </tr>
                    <tr>
                        <td><?php echo Yii::t('app', 'Discount');?></td>
                        <td>
                            <span><?= $form->field($model, 'invoice_discount')->textInput(['onChange'=>'change_amount();','style'=>'width:130px;float:left;margin-top:-16px;margin-left:12px'])->label(false) ?></span>
                            <span>%</span>
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo Yii::t('app', 'TAX');?></td>
                        <td><?php
                        if(!isset($model->invoice_gst))
                            $model->invoice_gst = $config->default_tax;
                        echo $form->field($model, 'invoice_gst')->dropDownList(ListSetup::getItemByList('Tax'),['onChange'=>'change_amount();','style'=>'width:130px;float:left;margin-top:-16px;margin-left:12px;'])->label(false) ?><span>%</span></td>
                    </tr>
                    <tr>
                        <td><?php echo Yii::t('app', 'Total');?></td>
                        <td><?php echo (isset(ListSetup::getItemByList('Currency')[$currency]) ? ListSetup::getItemByList('Currency')[$currency] : ""); ?> <span id="total"><?php echo ListSetup::getDisplayPrice($model->invoice_total_last_tax);?></span></td>
                    </tr>
                    <tr>
                        <td><?php echo Yii::t('app', 'Paid');?></td>
                        <td>
                            <?php echo (isset(ListSetup::getItemByList('Currency')[$currency]) ? ListSetup::getItemByList('Currency')[$currency] : ""); ?> <span id="paid"><?php echo ListSetup::getDisplayPrice($paid); ?></span>
                            <input hidden="" type="text" value="<?php echo $paid;?>" id="paid_value"/>
                        </td>
                    </tr>
                    <tr>
                        <td ><?php echo Yii::t('app', 'Outstanding');?></td>
                        <td>
                            <?php echo (isset(ListSetup::getItemByList('Currency')[$currency]) ? ListSetup::getItemByList('Currency')[$currency] : ""); ?> <span id="oustanding"><?php echo ListSetup::getDisplayPrice($model->invoice_total_last_paid);?></span>
                            <input hidden="" type="text" value="<?php echo $model->invoice_total_last_paid; ?>" id="oustanding_value" />
                        </td>
                    </tr>
                </table>

            </div>

<!-- ####### LIST PAYMENT ######## -->
<table class="table" id="table_payment" style="text-align: left;" cellspacing="4">
                <tr class="dautien invoice">
                    
                    <td width="15%" ><?php echo Yii::t('app', 'Payment No');?></td>
                    <td width="17%" ><?php echo Yii::t('app', 'Payment Date');?></td>
                    <td width="15%" ><?php echo Yii::t('app', 'Method');?></td>
                    <td style="text-align: left;width:15%"><?php echo Yii::t('app', 'Reference');?></td>
                    <td style="text-align: right;width:15%"><?php echo Yii::t('app', 'Amount');?></td>
                    <td width="15%"><?php echo Yii::t('app', 'Note');?></td>
                    <td width="15%"><?php echo Yii::t('app', 'Created By');?></td>
                    <td ></td>
                </tr>
                <?php 
                if(isset($invoicePayment)){
                    foreach($invoicePayment as $payment_item) { 
                            $class_void = "";
                            $void_payment = '<button type="button" onclick="void_receipt('.$payment_item->payment_id.',1,'.$model->invoice_id.');" id="button_checkin" class="btn btn-danger">'.Yii::t('app', 'Void Receipt').'</button>';
                            if($payment_item->payment_void == 1){
                                $class_void = "void";
                                $void_payment = Yii::t('app','Cancelled');
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
                        <td style="text-align: right"><?php echo (isset(ListSetup::getItemByList('Currency')[$currency]) ? ListSetup::getItemByList('Currency')[$currency] : ""); ?> <?php echo $ListSetup->getDisplayPrice($payment_item->payment_amount); ?></td>
                        <td><?php echo  (ListSetup::getItemByList('payment_note')[$payment_item->payment_note]) ? ListSetup::getItemByList('payment_note')[$payment_item->payment_note]:""; ?></td>
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
            <a style="float:left" href="#" id="new-payment" onclick="add_payment();return false;"><?php echo Yii::t('app', 'New Payment');?></a><br/>
            <?php } ?>
<!------  END LIST PAYMENT -->
            
            <div >
        </div>


    

    <div hidden="true">

        <input id='date_now' value="<?php echo date('Y-m-d h:i:s');?>" />
    </div>


<div class="parkclub-footer" style="text-align: center">
                <div class="form-group">
                        <?php if($model->invoice_status!=\app\modules\invoice\models\invoice::INVOICE_STATUS_VOID_INVOICE){ ?>
                            <?= Html::button($model->isNewRecord ? Yii::t('app', 'Paid') : Yii::t('app', 'Paid'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-success','name'=>"create",'id'=>'create-invoice']) ?>
                            <?php if($model->invoice_id){ ?>
                                <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Void invoice') : Yii::t('app', 'Void invoice'), ['class' => $model->isNewRecord ? 'btn btn-danger' : 'btn btn-danger','name'=>"void_invoice",'id'=>'void-invoice']) ?>
                            <?php } ?>
                        <?php } ?>
                <?php
                if($model->invoice_id){
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
                elseif(isset ($_GET['training_id'])){
                    $url_pdf= YII::$app->urlManager->createUrl("/invoice/default/printinvoice?id=". $model->invoice_id."&member_id=".$model->member_id."&book_id=0");
                    $url_pdf_a4= YII::$app->urlManager->createUrl("/invoice/default/printinvoice_a4?id=". $model->invoice_id."&member_id=".$model->member_id."&book_id=0");
                }

                $url_contract= YII::$app->urlManager->createUrl("/invoice/default/printcontract?id=". $model->invoice_id."&member_id=".$model->member_id."&book_id=".$book_id);
                ?>
                    <button type="button" onclick="popcheckin();" id="button_checkin" class="btn btn-success"><?php echo Yii::t('app', 'Print Invoice/Receipt');?></button>
                    <?php if($model->invoice_type == "membership" && $model->invoice_status == \app\modules\invoice\models\invoice::INVOICE_STATUS_PAID && $Member->member_barcode!="")
                    {?>
                    <button type="button" onclick="popInContract();" id="button_checkin" class="btn btn-success"><?php echo Yii::t('app', 'Print Contract');?></button>
                    <?php }?>    
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
                                <div id="bs-model-contract" class="modal fade bs-example-modal-lg popupCheckin" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div id="modal-content-checkin" class="modal-content">
                                            <br/><a target="_blank" style="margin-top:20px;" href='<?php echo $url_contract.'&template=1';?>' ><input type="button" class="btn btn-success" value="<?php echo Yii::t('app','Agreement English'); ?>"> </a>
                                            <a target="_blank" style="margin-top:20px;" href='<?php echo $url_contract;?>' ><input type="button" class="btn btn-success" value="<?php echo Yii::t('app','Agreement Vietnamese'); ?>"> </a>

                                        </div>
                                    </div>
                                </div>
                    <!-- END MODAL Print Contract -->
                <?php } ?>
            </div>
        </div>
    </div>
</div>
    <?php ActiveForm::end(); ?>
<script>
    $( document ).ready(function() {
        var intall_data = '<?php echo Yii::$app->session['install-data']; ?>';
        var istour = '<?php echo Yii::$app->session['tour']; ?>';
        var invoice_id = '<?php echo $model->invoice_id; ?>';
        if(intall_data==2 && istour==1){
            var invice_id = '<?php echo $model->invoice_id; ?>';
            tour_no_demo.restart();
            tour_no_demo.start();
            tour_no_demo.goTo(7);
            if(invice_id!="")
                location.href="/index.php/checkin/default/index";
        }
        var tour_step = '<?php echo Yii::$app->session['tour-step']; ?>';
        if(tour_step=='<?php echo app\models\Config::TOUR_MEMBER; ?>')
        {
            tour_member.restart();
            tour_member.start();
            tour_member.goTo(4);
            if(invoice_id!=""){
                tour_member.end();
                $('#bs-model-checkin-endtour').modal('show');
            }
        }
        
        $('#create-invoice').click(function(){
                $.blockUI();
				$('#form-invoice').submit();
        });
        
        $('#void-invoice').click(function(){
            if(confirm('<?php echo Yii::t('app', 'Are you sure to void this invoice?');?>')){
                $('#form-invoice').submit();
            }
            else
                return false;
        });
        
        var is_addinvoice_trainer = '<?php echo $is_addinvoice_trainer ?>';
        if(is_addinvoice_trainer==1){
            // $.blockUI();
            $('#create-invoice').click();
        }
        
        if(invoice_id=="" && $('#invoice_item_value').length )
            change_price();
        
    });
        var check=0;
        var count = 0;
        var next_number_payment;
        change_amount();
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
                alert('<?php echo Yii::t('app','Please Enter Quantity!'); ?>');
                check=1;
                return;
            }
            if(price=="")
            {
                alert('<?php echo Yii::t('app','Please Enter Price!'); ?>');
                check=1;
                return;
            }

            var amount = parseFloat(quantity) * parseFloat(price);
            var discount = $('#invoice-invoice_discount').val();
            discount = discount.split('.').join('');
            
            var discount_value = discount;
            var tax_value = $("#invoice-invoice_gst option:selected").text();
            tax_value = tax_value.split('.').join('');
            
            $('#sub_total').html(number_format(amount,0,",","."));
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
            paid = parseFloat(paid) + parseFloat(payment_input_amount);
                        
            oustanding = parseFloat(oustanding)-parseFloat(payment_input_amount);
            $('#oustanding_value').val(oustanding.toFixed(0));
            $('#paid').html(number_format(paid,0,",","."));
            $('#oustanding').html(number_format(oustanding,0,",","."));
            
        }
        function number_format( number, decimals, dec_point, thousands_sep ) {
            // * example 1: number_format(1234.5678, 2, '.', '');
            // * returns 1: 1234.57
            var n = number, c = isNaN(decimals = Math.abs(decimals)) ? 0 : decimals;
            var d = dec_point == undefined ? "," : dec_point;
            var t = thousands_sep == undefined ? "." : thousands_sep, s = n < 0 ? "-" : "";
            var i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "", j = (j = i.length) > 3 ? j % 3 : 0;

            var number = s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
            return number;
        }
        function popcheckin(){
            
            $('.modal-content').css({'min-height':'70px'});
            $('#bs-model-checkin').modal('show'); 
            
        }
        function popInContract(){
            
            $('.modal-content').css({'min-height':'70px'});
            $('#bs-model-contract').modal('show'); 
            
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
            change_amount();
        }
        
        function removeFormPayment(id){
            $('#form-payment-'+id).remove();
            change_amount();
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
                    if(confirm("<?php echo Yii::t('app','Are you sure to void this receipt?'); ?>"))
                        return true;
                    return false;
                },
                'data':{'payment_id':payment_id,'status':status,invoice_id:invoice_id},
                success:function(data){
                    if(data=='success')
                        location.reload();
                    else
                        alert('<?php echo Yii::t('app','void receipt error'); ?>');
                }
            });
        }
        function change_price(){
//            alert("123");
            var invoice_item_value = $('#invoice_item_value').val();
            var invoice_item_price = $('option:selected', '#invoice_item_value').attr('price');
            $('#price').val(invoice_item_price);
            change_amount();
        }
</script>