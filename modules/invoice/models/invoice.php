<?php

namespace app\modules\invoice\models;

use Yii;
use app\modules\invoice\models\Payment;
use app\modules\invoice\models\InvoiceItem;
use app\modules\members\models\Membership;
use app\modules\members\models\Members;

use app\models\ListSetup;
use \yii\db\Query;

/**
 * This is the model class for table "invoice".
 *
 * @property integer $invoice_id
 * @property string $invoice_no
 * @property string $invoice_date
 * @property integer $invoice_type_id
 * @property string $invoice_note
 * @property string $invoice_type
 * @property integer $member_id
 * @property integer $invoice_discount
 * @property integer $invoice_gst
 * @property integer $invoice_status
 * @property string $invoice_vat_date
 * @property string $invoice_vat_no
 * @property integer $invoice_vat_amount
 * @property integer $invoice_vat_status
 * @property string $invoice_subtotal
 * @property string $invoice_total_last_discount
 * @property string $invoice_total_last_tax
 * @property string $invoice_total_last_paid
 * @property string $invoice_gst_value
 */
class invoice extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    const INVOICE_STATUS_PAID = 'Paid';
    const INVOICE_STATUS_OUSTANDING = 'Outstanding';
    const INVOICE_STATUS_VOID_INVOICE = 'Void Invoice';
    const INVOICE_STATUS_GUEST_TREAT = 'Guest treat';
    
    public static function tableName()
    {
        return 'invoice';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['invoice_type', 'member_id','use_sale_id'], 'required'],
            [['invoice_date','invoice_vat_date','invoice_vat_amount','invoice_subtotal','invoice_total_last_discount','invoice_total_last_tax','invoice_total_last_paid','invoice_gst_value'], 'safe'],
            [['invoice_type_id','invoice_currency', 'member_id','created_by','invoice_term', 'invoice_discount', 'invoice_gst','invoice_vat_status','department_id' ], 'integer'],
            [['invoice_note','invoice_status'], 'string'],
            [['invoice_vat_no'], 'string', 'max' => 50],
            [['invoice_no'], 'string', 'max' => 255],
            [['invoice_type'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'invoice_id' => Yii::t('app','Invoice ID'),
            'invoice_no' => Yii::t('app','Receipt No'),
            'invoice_date' => Yii::t('app','Invoice Date'),
            'invoice_type_id' => Yii::t('app','Invoice Type ID'),
            'invoice_note' => Yii::t('app','Invoice Note'),
            'invoice_type' => Yii::t('app','Invoice Type'),
            'member_id' => Yii::t('app','Member Name'),
            'invoice_discount' => Yii::t('app','Invoice Discount'),
            'invoice_gst' => Yii::t('app','Invoice Gst'),
            'invoice_status' => Yii::t('app','Invoice Status'),
            'invoice_currency' => Yii::t('app','Invoice Status'),
            'invoice_term' => Yii::t('app','Invoice Status'),
            'created_by' => Yii::t('app','Invoice Status'),
            'use_sale_id' => Yii::t('app','Invoice Status'),
            'invoice_date'=>Yii::t('app','VAT Invoice Date'),
            'invoice_vat_amount'=>Yii::t('app','VAT Invoice Amount'),
            'invoice_vat_no'=>Yii::t('app','VAT Invoice No'),
            'invoice_vat_status'=>Yii::t('app','VAT Status'),
            'invoice_subtotal'=>Yii::t('app', 'SubTotal'),
            'invoice_total_last_discount'=>Yii::t('app', 'Total Last Discount'),
            'invoice_total_last_tax'=>Yii::t('app', 'Total Last Tax'),
            'invoice_total_last_paid'=>Yii::t('app', 'Total Last Paid'),
            'invoice_gst_value'=>Yii::t('app', 'Tax'),
            'department_id'=>Yii::t('app', 'department id')
        ];
    }
    
    public function getStatusInvoice($invoice_id=false)
    {
        if(!$invoice_id)
            $invoice_id = $this->invoice_id;
        $payment = new Payment();
        $invoice = invoice::findOne($invoice_id);
        $amount_payment_invoice = $payment->getAmountByInvoice($invoice_id);
        $subtotalInvoice = $this->getSubtotalInvocie($invoice_id);
        $oustanding = $subtotalInvoice-$amount_payment_invoice;
        if($oustanding<1)
        {
            $status = self::INVOICE_STATUS_PAID;
        }
        else
            $status=self::INVOICE_STATUS_OUSTANDING;
        return $status;
    }
    
    public function updateStatusInvocie($invoice_id){
        $invoice = invoice::findOne($invoice_id);
        $status = $this->getStatusInvoice($invoice_id);
        if($status == self::INVOICE_STATUS_PAID)
            $invoice->invoice_status = self::INVOICE_STATUS_PAID;
        if($status == self::INVOICE_STATUS_OUSTANDING)
            $invoice->invoice_status = self::INVOICE_STATUS_OUSTANDING;
        if($invoice->save()){
            return true;
        }
        return false;
        
    }

    public function getSubtotalInvocie($invoice_id)
    {
        $listSetup = new ListSetup();
        $list_discount = ListSetup::getItemByList('Discount');
        $list_tax = ListSetup::getItemByList('Tax');
        $invoiceItem = new InvoiceItem();
        
        $amountInvoiceItem = $invoiceItem->getAmountInvoice($invoice_id);
        $invoice = invoice::findOne($invoice_id);
        $discount  = $invoice['invoice_discount'];
        $discount_value = $discount;
        $gst_value = (isset($list_tax[$invoice['invoice_gst']]) ? $list_tax[$invoice['invoice_gst']] : "");
        
        $total_discount = ($amountInvoiceItem*$discount_value)/100;
        $total_tax = (($amountInvoiceItem-$total_discount)*$gst_value)/100;
        
        
        $subtotal = $amountInvoiceItem - $total_discount + $total_tax;
        return $subtotal;
    }
    
    public function getDisplayInvoiceStatus($status)
    {
        if($status==self::INVOICE_STATUS_PAID)
            return 'Paid';
        if($status==self::INVOICE_STATUS_OUSTANDING)
            return 'Oustanding';
        if($status==self::INVOICE_STATUS_WRITTEN_OFF)
            return 'Written off';
        
    }
    
    public function getInvoice($membership_type_id,$invoice_type)
    {
        $invoice = Invoice::find();
        if($membership_type_id)
        {
            $invoice->andWhere(['invoice_type_id'=>$membership_type_id]);
        }
        if($invoice_type)
        {
            $invoice->andWhere(['invoice_type'=>$invoice_type]);
        }
        $invoice->addOrderBy(['invoice_status'=>'DESC']);
//        $invoice->andFilterWhere(['!=', 'invoice_status', invoice::INVOICE_STATUS_VOID_INVOICE]);
        $invoice=$invoice->all();
        if($invoice)
            return $invoice;
        return false;
    }
    
    public function getInoviceByGuest($member_id){
        $invoice = Invoice::find();
        $invoice->andWhere('invoice_type_id is NULL OR invoice_type_id = 0');
        if($member_id)
        {
            $invoice->andWhere(['member_id'=>$member_id]);
        }
        $invoice->addOrderBy(['invoice_status'=>'DESC']);
//        $invoice->andFilterWhere(['!=', 'invoice_status', invoice::INVOICE_STATUS_VOID_INVOICE]);
        $invoice=$invoice->all();
        if($invoice)
            return $invoice;
        return false;
    }


    public function getInvoiceOustanding($invoice_id)
    {
        $payment = new Payment();
        $paid = $payment->getAmountByInvoice($invoice_id);
        $oustanding = round($this->getSubtotalInvocie($invoice_id)) - round($paid);
        return $oustanding;
    }
    
    public function getAllAmountInvoice($status=false,$dataprovi=false)
    {
        $total_amount = 0;
        if($status)
            $allInvoice = invoice::findAll (['invoice_status'=>$status]);
        if($dataprovi)
            $allInvoice=$dataprovi->models;
        foreach ($allInvoice as $data)
        {
            $amount_invoice = $data;
            $total_amount+=$data->invoice_total_last_tax;
        }
        return $total_amount;
    }
    
    public function getAllPrice($status=false,$dataprovie = false)
    {
        $total_amount = 0;
        $invoiceItem  = new InvoiceItem();
        if($status)
            $allInvoice = invoice::findAll (['invoice_status'=>$status]);
        if($dataprovie)
            $allInvoice=$dataprovie->models;
        foreach ($allInvoice as $data)
        {
            $amount_invoice = $invoiceItem->getAmountInvoice($data['invoice_id']);
            $total_amount+=$amount_invoice;
        }
        return $total_amount;
    }
    
    public function getAllAmountDiscount($status=false,$dataprovie = false)
    {
        $total_amount = 0;
        
        if($status)
            $allInvoice = invoice::findAll (['invoice_status'=>$status]);
        if($dataprovie)
            $allInvoice=$dataprovie->models;
        foreach ($allInvoice as $data)
        {
           $total_amount += $data->getDiscountRecord();
        }
        return $total_amount;
    }
    //lay invoice chua thanh toan het co status la oustanding/paid/witen off
    public function getAmountPaymentInvoice($status=false,$start_date=false,$end_date=false,$dataprovi=false)
    {
        if($status)
            $allInvoice = invoice::findAll (['invoice_status'=>$status]);
        if($dataprovi)
            $allInvoice=$dataprovi->models;
        $total_payment = 0;
        $payment = new Payment();
        
        foreach ($allInvoice as $data)
        {
            $paid = $payment->getAmountByInvoice($data['invoice_id']);
            $total_payment+=$paid;
        }
        return $total_payment;
    }
    
    public function getAllInvoice($invoice_id=false,$status=false,$start_date=false,$end_date=false)
    {
        $query = new Query();
        if($invoice_id)
             $query->andFilterWhere(['=', 'invoice_id', $invoice_id]);
        if($start_date)
        {
            $query->andFilterWhere(['>=', 'invoice_date', $start_date]);
        }
        if($end_date)
        {
            $query->andFilterWhere(['<=', 'invoice_date', $end_date]);
        }
        if($status)
        {
            $query->andFilterWhere(['<=', 'invoice_date', $end_date]);
        }
        $this->load();
    }


    public function getAmountOustandingInvoice($status,$dataprovi=false)
    {
        $total_amount = 0;
        if($status)
            $allInvoice = invoice::findAll (['invoice_status'=>$status]);
        if($dataprovi)
            $allInvoice=$dataprovi->models;
        foreach ($allInvoice as $data)
        {
            $amount_invoice = $data;
            $total_amount+=$data->invoice_total_last_paid;
        }
        return $total_amount;
    }
    
    //Tinh do tuoi cua invoice
    public function getAgeInvoice($invoice_id)
    {
        $invoice = invoice::findOne($invoice_id);
        $invocie_date = $invoice['invoice_date'];
        $listSetup = new \app\models\ListSetup();
        $day_return = $listSetup->getDate($invocie_date);
        return $day_return['diff'];
    }
    
    
   public function delete() {
        $content = serialize($this->find()->asArray()->one());
        $result = parent::delete();
        
        if($result){
            InvoiceItem::deleteAll(["invoice_id"=>$this->invoice_id]);
            $record_id = $this->invoice_id;
            $table_name = $this->tableName();
            $module = 'Invoice';
            $action = 'Delete';
            $number_no = "";
            $description = $action .' Invoice '.$number_no;
            $history = new \app\modules\history\models\History ();
            $history->addHistory($record_id, $table_name, $module, $action, $description,$content);
        }
        
        return $result;
    }
    
    public function save($runValidation = true, $attributeNames = null) {
        $result = parent::save($runValidation, $attributeNames);
        
        if($result){
            $record_id = $this->invoice_id;
            $table_name = $this->tableName();
            $module = 'invoice';
            $action = (($this->isNewRecord) ? 'Add' : 'Update');
            $number_no = $this->invoice_no;
            $description = $action .' invoice '.$number_no;
            $history = new \app\modules\history\models\History ();
            $content = serialize($this->find()->asArray()->one());
            $history->addHistory($record_id, $table_name, $module, $action, $description,$content);
        }
        
        return $result;
    }
    
    // get total bill
    public function getTotalBill($invoice_id=false)
    {
        if($invoice_id)
        {
            $amount=0;
            $invoiceItem = InvoiceItem::find()->where(["invoice_id"=>$invoice_id])->one();
			$invoice_item_quantity = 0;
            if(isset($invoiceItem)){

                $amount = $invoiceItem->invoice_item_price;
                $invoiceItem = InvoiceItem::find()->where(["invoice_id"=>$invoice_id])->one();
				if(isset($invoiceItem)){

                    $invoice_item_quantity = $invoiceItem->invoice_item_quantity;

                }
            }
            $result = $amount * $invoice_item_quantity;
        }
        return $result;
    }
    
    //get discount
    public function getDiscount($invoice_id)
    {
        $discount_amount=0;
        $tax_amount=0;
		$amount=0;
        $invoiceItem = InvoiceItem::find()->where(["invoice_id"=>$invoice_id])->one();
        $invoice = invoice::findOne($invoice_id);
        if($invoiceItem) {
			$amount = $invoiceItem->invoice_item_amount;
		}
        $discount_value = $invoice->invoice_discount;
        $discount_amount = ($amount*$discount_value)/100;
        return $discount_amount;
    }
  
    //before tax
    public function getBeforeTax($invoice_id)
    {
        $getBillTotal = $this->getTotalBill($invoice_id);
        $getDiscount = $this->getDiscount($invoice_id);
        return $getBillTotal-$getDiscount;
    }
    
    public function getDropdownStatus(){
        $arr = array();
        $arr[self::INVOICE_STATUS_PAID] = Yii::t('app', self::INVOICE_STATUS_PAID);
        $arr[self::INVOICE_STATUS_OUSTANDING] = Yii::t('app', self::INVOICE_STATUS_OUSTANDING);
        $arr[self::INVOICE_STATUS_GUEST_TREAT] = Yii::t('app', self::INVOICE_STATUS_GUEST_TREAT);
        return $arr;
    }
    
    public function getInvoiceOneByEntry($entry_id,$entry_type){
        $invoice = invoice::find()->where(['invoice_type_id'=>$entry_id,'invoice_type'=>$entry_type])->one();
        return $invoice;
    }
    
    public function getDiscountRecord(){
        return ($this->invoice_subtotal * $this->invoice_discount)/100;
    }
    
    public function getTaxRecord(){
        return ($this->invoice_total_last_discount * $this->invoice_gst_value)/100;
    }
    
    public function update_total($invoice_id){
        $invoice = invoice::findOne($invoice_id);
        $invoice_item = new InvoiceItem();
        $payment = new Payment();
        $invoice->invoice_subtotal = $invoice_item->getAmountInvoice($invoice_id);
        $invoice->invoice_total_last_discount = $invoice->invoice_subtotal - $invoice->getDiscountRecord();
        $invoice->invoice_total_last_tax = $invoice->invoice_total_last_discount + $invoice->getTaxRecord();
        $invoice->invoice_total_last_paid = $invoice->invoice_total_last_tax - $payment->getAmountByInvoice($invoice_id,true);
        if($invoice->save())
            return true;
        return false;
    }
    
    public function getAmountLastTaxItem($tax,$price,$discount){
        if($tax != 0){
            $amount_last_discount = $price - $price*$discount/100;
            return $amount = $amount_last_discount + $amount_last_discount*$tax/100;
        }else{
            return $price - $price*$discount/100;
        }
    }
    
    public function getDuaDate(){
        $dua_date = "";
        if($this->invoice_term >= 0){
            $date = $this->invoice_date;
            $day = (isset(ListSetup::getItemByList('Term')[$this->invoice_term])) ? ListSetup::getItemByList('Term')[$this->invoice_term] : "";
            if($day!=""){
                $new_date = strtotime ( $day , strtotime ( $date ) ) ;
                $dua_date = date ( 'd/m/Y' , $new_date );
            }
        }
        return $dua_date;
    }

    public function getInvoiceItem(){
        return $this->hasMany(InvoiceItem::className(), ['invoice_id' => 'invoice_id']);
    }

    public function getMembership(){
        return $this->hasOne(Membership::className(),["membership_id"=>'invoice_type_id']);
    }

    public function getPayment(){
        return $this->hasMany(Payment::className(),["invoice_id"=>'invoice_id']);
    }

    public function getMember(){
        return $this->hasOne(Members::className(),["member_id"=>'member_id']);
    }
}
