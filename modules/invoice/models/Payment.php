<?php

namespace app\modules\invoice\models;


use Yii;
use yii\data\ActiveDataProvider;
use app\modules\invoice\models\invoice;
use app\modules\invoice\models\InvoiceItem;
/**
 * This is the model class for table "payment".
 *
 * @property integer $payment_id
 * @property string $payment_date
 * @property string $payment_amount
 * @property string $payment_note
 * @property string $payment_no
 * @property integer $member_id
 * @property integer $invoice_id
 * @property integer $payment_void
 * @property string $payment_void_date
 * @property integer $name $payment_void_parent
 * @property integer $created_by
 * @property integer $deposit_id
 */
class Payment extends \yii\db\ActiveRecord
{
    const PAYMENT_VOID =1;
    const PAYMENT_VOID_LABEL = 'Void Receipt';
	public $member_ids = array();
    /**
     * @inheritdoc
     */
    const LB_PAYMENT_NUMBER_MAX = 11;
    
    public static function tableName()
    {
        return 'payment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['payment_date','payment_method', 'payment_no', 'member_id', 'invoice_id'], 'required'],
            [['payment_date'], 'safe'],
            [['payment_amount','payment_void','deposit_id'], 'number'],
            [['payment_note','payment_void_date'], 'string'],
            [['member_id', 'invoice_id','payment_void_parent','created_by'], 'integer'],
            [['payment_no'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'payment_id' => Yii::t('app','Payment ID'),
            'payment_date' => Yii::t('app','Payment Date'),
            'payment_amount' => Yii::t('app','Payment Amount'),
            'payment_note' => Yii::t('app','Payment Note'),
            'payment_no' => Yii::t('app','Payment No'),
            'member_id' => Yii::t('app','Member ID'),
            'invoice_id' => Yii::t('app','Invoice ID'),
            'payment_method' => Yii::t('app','Invoice ID'),
            'payment_void'=>Yii::t('app','Void Payment'),
            'payment_void_date'=>Yii::t('app','Payment Void Date'),
            'created_by'=>Yii::t('app','Create By'),
            'deposit_id'=>Yii::t('app', 'Deposit')
        ];
    }
    

    public function FormatPaymentNo($paymentNextNo){
        
        // R-YYYY
        
        $paymentNextNo=$paymentNextNo+1;
        
        
        
        return 'R-'.$paymentNextNo;
    }
    
    function get_numerics ($str) {
        preg_match_all('/\d+/', $str, $matches);
       
        return $matches[0];
    }
    
    public function getPayment($member_id=false,$start_date=false,$end_date=false,$not_void=false)
    {
        $payment = Payment::find();
        if($member_id)
            $payment->andWhere (['member_id'=>$member_id]);
        if($start_date)
        {
           
            $payment->andFilterWhere(['>=', 'payment_date', $start_date]);
        }
        if($end_date)
        {
           
            $payment->andFilterWhere(['<=', 'payment_date', $end_date]);
        }
        if($not_void)
            $payment->andFilterWhere(['=', 'payment_void', 0]);
        if($payment->all())
            return $payment->all ();
        return false;
    }
    public function getPaymentLast()
    {
        $payment = Payment::findBySql("SELECT * FROM payment ORDER BY payment.payment_no DESC LIMIT 0 , 1")->one();
        
        if($payment)
        {
            return $payment['payment_no'];
        }
        
        $createYear = date('Y');
        return $createYear.'000000';
    }
    
    public function getAmountByInvoice($invoice_id,$void_payment=false)
    {
        $allPmentByInvoice=array();
        if($void_payment)
            $allPmentByInvoice = Payment::findAll(['invoice_id'=>$invoice_id]);
        else
            $allPmentByInvoice = Payment::findAll(['invoice_id'=>$invoice_id,'payment_void'=>0]);
        $amount=0;
        foreach ($allPmentByInvoice as $value) {
            $amount+=$value['payment_amount'];
        }
        return $amount;
    }
    
    public function search($params,$start_date=false,$end_date=false,$payment_revenue_type=false, $payment_method=false,$month=false,$year=false)
    {
        $query = Payment::find();

        // add conditions that should always apply here
        $query->orderBy(['payment_date' => SORT_ASC]);
        $query->join("INNER JOIN", "invoice", 'payment.invoice_id=invoice.invoice_id');
        // $query ->andFilterWhere(['!=', 'invoice.member_id', 0]);
//        if($payment_revenue_type == 'Guest')
//        {
//            $query->join("INNER JOIN", "invoice_item", 'invoice.invoice_id=invoice_item.invoice_id');
//            $query->andFilterWhere(['=', 'invoice_item.invoice_item_description', InvoiceItem::INVOICE_ITEM_GUEST]);
//        }
        if($payment_revenue_type == 'other'){
            $query->join("INNER JOIN", "invoice_item", 'invoice.invoice_id=invoice_item.invoice_id');
            $query->andFilterWhere(['=', 'invoice_item.invoice_item_description', InvoiceItem::INVOICE_ITEM_OTHERS]);
        }
        else if($payment_revenue_type == 'Membership'){
            $query->join("INNER JOIN", "invoice_item", 'invoice.invoice_id=invoice_item.invoice_id');
            $query->andFilterWhere(['like', 'invoice_item.invoice_item_description', 'Membership:']);
        }
        else if($payment_revenue_type=='booking'){
            $query->andFilterWhere(['=', 'invoice.invoice_type', $payment_revenue_type]);
        }
        else if($payment_revenue_type=='pos'){
            $query->andFilterWhere(['=', 'invoice.invoice_type', $payment_revenue_type]);
        }
        elseif($payment_revenue_type!=""){
            $array_item = array("0"=>'0')+InvoiceItem::getArrayItemRevenueNew($payment_revenue_type);
            $query->join("INNER JOIN", "invoice_item", 'invoice.invoice_id=invoice_item.invoice_id');
            $query->andFilterWhere(['IN', 'invoice_item.invoice_item_description', $array_item]);
        }
        if(isset($payment_method)) {
            $query->andFilterWhere(['like', 'payment.payment_method', $payment_method]);
        }
        if($start_date)
        {
           
            $query->andFilterWhere(['>=', 'date(payment_date)', $start_date]);
        }
        if($end_date)
        {
           
            $query->andFilterWhere(['<=', 'date(payment_date)', $end_date]);
        }
        if($this->member_id)
            $query->andFilterWhere (['=','payment.member_id',$this->member_id]);
		if($this->member_ids && count($this->member_ids) >0)
            $query->andFilterWhere (['in','payment.member_id',$this->member_ids]);
        if($this->created_by)
            $query->andFilterWhere (['=','payment.created_by',$this->created_by]);
        
        if($month)
            $query->andFilterWhere (['=','MONTH(payment_date)',$month]);
        if($year)
            $query->andFilterWhere (['=','YEAR(payment_date)',$year]);
        
        if($this->deposit_id)
            $query->andFilterWhere (['=','payment.deposit_id',$this->deposit_id]);
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        
        $this->load($params);
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        
        // grid filtering conditions
        $query->andFilterWhere([
            'payment_id' => $this->payment_id,
            'payment_date' => $this->payment_date,
            'payment_amount' => $this->payment_amount,
            'payment_note' => $this->payment_note,
            'payment_no' => $this->payment_no,
            'member_id' => $this->member_id,
            'invoice_id' => $this->invoice_id,
            'payment_method' => $this->payment_method,
            'deposit_id'=>$this->deposit_id
        ]);
        
        
        return $dataProvider;
    }
	
	public function getInvoice(){
		return $this->hasOne(Invoice::className(), ['invoice_id' => 'invoice_id']);
	}
    
    public function search_guest($params,$start_date=false,$end_date=false){
        $query = Payment::find();
        $query  ->select([]) 
                ->from('payment')
                ->join( 'INNER JOIN',
                        'members',
                        'members.member_id =payment.member_id'
                        );
     
              $query ->Where('members.member_barcode = ""');
		$query ->Where('members.guest_code <> ""');
              $query ->andwhere('members.is_trainer = 0');
        if($start_date)
        {
           
            $query->andFilterWhere(['>=', 'date(payment_date)', $start_date]);
        }
        if($end_date)
        {
           
            $query->andFilterWhere(['<=', 'date(payment_date)', $end_date]);
        }
       
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        
        
        return $dataProvider;
    }
    
    public function getPaymentAmount($start_date = false,$end_date=false,$method=false)
    {
        $params=  Yii::$app->params;
        $payment = $this->search($params, $start_date, $end_date,false,$method);
        $amount = 0;
        foreach ($payment->models as $data)
        {
            $amount+=$data['payment_amount'];
        }
       
        return $amount;
    }
    
    
    public function getTotalPaymentHasInvoice($start_date = false,$end_date=false)
    {
        $params=  Yii::$app->params;
        $payment = $this->search($params, $start_date, $end_date);
        $total_amount = 0;
        $invoice = new invoice();
        foreach ($payment->models as $data)
        {
            
            $amount=$invoice->getSubtotalInvocie($data->invoice_id);
            $total_amount+=$amount;
        }
        return $total_amount;
    }
    
    public function delete() {
        $content = serialize($this->find()->asArray()->one());
        $result = parent::delete();
        
        if($result){
            $record_id = $this->payment_id;
            $table_name = $this->tableName();
            $module = 'Invoice';
            $action = 'Delete';
            $number_no = $this->payment_no;
            $description = $action .' Payment '.$number_no;
            $history = new \app\modules\history\models\History ();
            $history->addHistory($record_id, $table_name, $module, $action, $description,$content);
        }
        
        return $result;
    }
    
    public function save($runValidation = true, $attributeNames = null) {
        $result = parent::save($runValidation, $attributeNames);
        
        if($result){
            $record_id = $this->payment_id;
            $table_name = $this->tableName();
            $module = 'invoice';
            $action = (($this->isNewRecord) ? 'Add' : 'Update');
            $number_no = $this->payment_no;
            $description = $action .' payment '.$number_no;
            $content = serialize($this->find()->asArray()->one());
//            $history = new \app\modules\history\models\History ();
//            $history->addHistory($record_id, $table_name, $module, $action, $description,$content);
        }
        
        return $result;
    }
    
    //get payment of membership - Membership Revenue
    public function getPaymentAmountMembership($dataProvider,$invoice_type=false)
    {
        $amount_mambership = 0;
        $rental_revenue = 0;
        $retail_revenue = 0;
        $InvoiceItem = new InvoiceItem();
        $arrItemInvoice = $InvoiceItem->getArrayItemInvoice();
        $amount=0;
        foreach ($dataProvider->models as $data)
        {
            $invoice_id = $data->invoice_id;
            $invoiceInfo  = invoice::findOne($invoice_id);
            $invoiceItemInfo = $InvoiceItem->getInvoiceItem($invoice_id);
            if($invoiceInfo['invoice_type'] == 'membership')
            {
                if(in_array(trim($invoiceItemInfo['invoice_item_description']), $arrItemInvoice))
                    $retail_revenue+=$data['payment_amount'];
                else        
                    $amount_mambership+=$data['payment_amount'];
            }
            else {
                $rental_revenue+=$data['payment_amount'];
            }
//            $amount+=$data['payment_amount'];
        }
        
        if($invoice_type == 'membership_revenue')
            return $amount_mambership;
        if($invoice_type == 'rental_revenue')
            return $rental_revenue;
        if($invoice_type == 'retail_revenue')
            return $retail_revenue;
        
    }
    
    public function voidPayment($payment_id){
        $payment_void = Payment::findOne($payment_id);
        $payment_void->payment_void = 1;
        if($payment_void->save()){
            $payment = new Payment();
            $payment->payment_id = "";
            $payment->payment_void_date = date('Y-m-d H:i:s');
            $payment->payment_no = $payment_void->payment_no;
            $payment->payment_void = Payment::PAYMENT_VOID;
            $payment->invoice_id = $payment_void->invoice_id;
            $payment->payment_method = $payment_void->payment_method;
            $payment->created_by = $payment_void->created_by;
            $payment->member_id = $payment_void->member_id;
            $payment->reference = $payment_void->reference;
            $payment->payment_amount = -$payment_void->payment_amount;
            $payment->payment_date = $payment_void->payment_date;
            $payment->payment_note = $payment_void->payment_note;
            $payment->payment_void_parent = $payment_void->payment_id;
            $payment->save();
                return true;
        }
        return false;
    }


    public function voidPaymentByInvoice($invoice_id){
        $allPmentByInvoice=array();
        if($invoice_id){
            $allPmentByInvoice = Payment::findAll(['invoice_id'=>$invoice_id]);
            foreach ($allPmentByInvoice as $value) {
                $this->voidPayment($value->payment_id);
            }
            return true;
        }
        return false;
    }
    
    public function getPaymentAmountByMethod($dataProvider,$method)
    {
        $amount=0;
        foreach ($dataProvider->models as $data)
        {
            if($method==$data['payment_method'])
                $amount+=$data['payment_amount'];
        }
       
        return $amount;
    }
    
    public function getTotalCollection($month=false,$year=false,$revenue_type=false){
        $payment = new Payment();
        $dataProvider = $payment->search(Yii::$app->request->queryParams,false,false,$revenue_type,false,$month,$year);
        $dataProvider->pagination->pageSize=0;
        $total = 0;
        foreach ($dataProvider->models as $item) {
            $total += $item->payment_amount;
        }
        return $total;
    }
   
    
}
