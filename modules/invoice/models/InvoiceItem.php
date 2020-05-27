<?php

namespace app\modules\invoice\models;

use app\modules\revenue_type\models\RevenueItem;
use Yii;
use yii\data\ActiveDataProvider;
use \yii\db\Query;

/**
 * This is the model class for table "invoice_item".
 *
 * @property integer $invoice_item_id
 * @property integer $invoice_id
 * @property string $invoice_item_description
 * @property string $invoice_item_amount
 * @property integer $invoice_item_entity_id
 * @property string $invoice_item_tax
 * @property string $invoice_item_note
 * @property integer $invoice_item_printed
 * @property integet $payment_id
 * @property integer $invoice_item_delete
 */
class InvoiceItem extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
//    const INVOICE_ITEM_MBS = 'MBS';
    const INVOICE_ITEM_GUEST = 'Guest';
    const INVOICE_ITEM_SUSPENSION = 'Suspension';
    const INVOICE_ITEM_CARD = 'Card Replacement';
    const INVOICE_ITEM_TRANSFER = 'Transfer';
    const INVOICE_ITEM_OTHERS = 'Others';
    const INVOICE_ITEM_UPGRADE = 'Upgrade';
    const INVOICE_ITEM_DOWNGRADE = 'Downgrade';
    const INVOICE_ITEM_OVERNIGHT_PARKING_FEES = 'Overnight parking fees';

    public static function tableName()
    {
        return 'invoice_item';
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[ 'invoice_id', 'invoice_item_description', 'invoice_item_amount'], 'required'],
            [['invoice_item_id', 'invoice_id'], 'integer'],
            [['invoice_item_description','invoice_item_note'], 'string'],
            [['invoice_item_amount','invoice_item_price','invoice_item_entity_id','invoice_item_tax','invoice_item_printed',
                'payment_id','payment_qty_id','invoice_item_delete'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'invoice_item_id' => Yii::t('app','Invoice Item ID'),
            'invoice_id' => Yii::t('app','Invoice ID'),
            'invoice_item_description' => Yii::t('app','Invoice Item Description'),
            'invoice_item_amount' => Yii::t('app','Invoice Item Amount'),
            'invoice_item_quantity' => Yii::t('app','Invoice Item Amount'),
            'invoice_item_price' => Yii::t('app','Invoice Item Amount'),
            'invoice_item_entity_id'=>'invoice_item_id'
        ];
    }
    
    public function getAmountInvoice($invoice_id)
    {
        
        $invoice_item = InvoiceItem::find()->select('invoice_item_amount')->where(['invoice_id'=>$invoice_id])->all();
        $total_amount = 0;
        foreach ($invoice_item as $value) {
            
            $amount = $value['invoice_item_amount'];
            $total_amount+=$amount;
        }
        
        return $total_amount;
    }
    
   public function delete() {
        $content = serialize($this->find()->asArray()->one());
        $result = parent::delete();
        
        if($result){
            $record_id = $this->invoice_item_id;
            $table_name = $this->tableName();
            $module = 'invoice';
            $action = 'Delete';
            $number_no = "";
            $description = $action .' invoiceItem '.$number_no;
            $history = new \app\modules\history\models\History ();
            $history->addHistory($record_id, $table_name, $module, $action, $description,$content);
        }
        
        return $result;
    }
    
    public function save($runValidation = true, $attributeNames = null) {
        $result = parent::save($runValidation, $attributeNames);
        
        if($result){
            $record_id = $this->invoice_item_id;
            $table_name = $this->tableName();
            $module = 'Invoice';
            $action = (($this->isNewRecord) ? 'Add' : 'Update');
            $number_no = "";
            $description = $action .' Invoice Item '.$number_no;
            $history = new \app\modules\history\models\History ();
            $content = serialize($this->find()->asArray()->one());
            $history->addHistory($record_id, $table_name, $module, $action, $description,$content);
        }
        
        return $result;
    }
    
    //list inmvoice item descriptiion
    public function setListItemInvoice($membership_type=false, $selected=false,$invoice_date=false)
    {
        $InfomembershipType = false;
        $modelRevenueitem = new \app\modules\revenue_type\models\RevenueItem();
        $revenue = \app\modules\revenue_type\models\Revenue::find()->where(["entry_type"=>1])->all();
        $MembershipPrice = new \app\modules\membership_type\models\MembershipPrice();
        $InfoPrice = array();
        $price = 0;
        if($membership_type){
            $InfomembershipType= \app\modules\membership_type\models\MembershipType::findOne($membership_type);
            $InfoPrice= $MembershipPrice->getPriceByMembershipType($membership_type,$invoice_date);
            if(count($InfoPrice) > 0 && $InfoPrice[0]['membership_price'] >0)
            {
                $price = $InfoPrice[0]['membership_price'];

            }
        }
        $description="<select id='invoice_item_value' class='form-control' style='margin:0' onchange='change_price();'>";
        if($InfomembershipType)
        {
            $description .= '<option price="'. \app\models\ListSetup::getDisplayPrice($price).'" value="Membership: '.$InfomembershipType->membership_name.'">Membership:'.$InfomembershipType->membership_name.'</option>';
        }
//        $description.='<option value='.InvoiceItem::INVOICE_ITEM_GUEST.'>'.InvoiceItem::INVOICE_ITEM_GUEST.'</option>';
        
        foreach ($revenue as $item) {
            $description.='<optgroup label="'.$item->revenue_name.'"></optgroup>';
            $revenueitem = RevenueItem::find()->where(["revenue_id"=>$item->revenue_id])->all();
            foreach ($revenueitem as $item) {
                $description.='<option value="'.$item->revenue_item_name.'" price="'.\app\models\ListSetup::getDisplayPrice($item->revenue_item_price).'">'.$item->revenue_item_name.'</option>';
            }
        }

        
//        $description.='<option value='.InvoiceItem::INVOICE_ITEM_SUSPENSION.'>'.InvoiceItem::INVOICE_ITEM_SUSPENSION.'</option>';
//        $description.='<option value="'.InvoiceItem::INVOICE_ITEM_CARD.'">'.InvoiceItem::INVOICE_ITEM_CARD.'</option>';
//        $description.='<option value='.InvoiceItem::INVOICE_ITEM_TRANSFER.'>'.InvoiceItem::INVOICE_ITEM_TRANSFER.'</option>';
//        $description.='<option value='.InvoiceItem::INVOICE_ITEM_UPGRADE.'>'.InvoiceItem::INVOICE_ITEM_UPGRADE.'</option>';
//        $description.='<option value='.InvoiceItem::INVOICE_ITEM_DOWNGRADE.'>'.InvoiceItem::INVOICE_ITEM_DOWNGRADE.'</option>';
//        $description.='<option value="'.InvoiceItem::INVOICE_ITEM_OVERNIGHT_PARKING_FEES.'">'.InvoiceItem::INVOICE_ITEM_OVERNIGHT_PARKING_FEES.'</option>';
        $description.='<option value='.InvoiceItem::INVOICE_ITEM_OTHERS.'>'.InvoiceItem::INVOICE_ITEM_OTHERS.'</option>';
        $description.="</select>";
        return $description;
    }
    
    public function getInvoiceItem($invoice_id=false)
    {
        $InvoiceItem = InvoiceItem::findOne(['invoice_id'=>$invoice_id]);
        return $InvoiceItem;
    }
    
    public function getArrayItemInvoice()
    {
        $listItem= array(InvoiceItem::INVOICE_ITEM_GUEST,InvoiceItem::INVOICE_ITEM_SUSPENSION
                ,InvoiceItem::INVOICE_ITEM_CARD,InvoiceItem::INVOICE_ITEM_TRANSFER,InvoiceItem::INVOICE_ITEM_OTHERS,
                InvoiceItem::INVOICE_ITEM_UPGRADE,InvoiceItem::INVOICE_ITEM_DOWNGRADE,InvoiceItem::INVOICE_ITEM_OVERNIGHT_PARKING_FEES);
            return $listItem;
    }
    
    static public function getArrayItemRevenueAdminFees(){
        $listItem= array(InvoiceItem::INVOICE_ITEM_SUSPENSION
                ,InvoiceItem::INVOICE_ITEM_CARD,InvoiceItem::INVOICE_ITEM_TRANSFER,
                InvoiceItem::INVOICE_ITEM_UPGRADE,InvoiceItem::INVOICE_ITEM_DOWNGRADE,InvoiceItem::INVOICE_ITEM_OVERNIGHT_PARKING_FEES);
            return $listItem;
    }
    static public function getArrayItemRevenue($model){
        $listItem= array(InvoiceItem::INVOICE_ITEM_SUSPENSION
                ,InvoiceItem::INVOICE_ITEM_CARD,InvoiceItem::INVOICE_ITEM_TRANSFER,
                InvoiceItem::INVOICE_ITEM_UPGRADE,InvoiceItem::INVOICE_ITEM_DOWNGRADE,InvoiceItem::INVOICE_ITEM_OVERNIGHT_PARKING_FEES);
            return $listItem;
//       $adminfees = RevenueItem::find()->where(["revenue_id"=>$model])->all();
//                        $array = "";
//                        foreach ($adminfees as $key=> $data) {
//                                $array[$data->revenue_item_id] = $data->revenue_item_name;
//                            }
//                            return $array;
    }
    
    static public function getArrayItemRevenueNew($revenue_id){
        $revenue = \app\modules\revenue_type\models\Revenue::findOne($revenue_id);
        $item = RevenueItem::find()->where(["revenue_id"=>$revenue_id])->all();
        $array = array();
        foreach ($item as $key=> $data) {
                $array[$data->revenue_item_name] = $data->revenue_item_name;
            }
        if($revenue && $revenue->revenue_name==InvoiceItem::INVOICE_ITEM_GUEST)
            $array[InvoiceItem::INVOICE_ITEM_GUEST] = InvoiceItem::INVOICE_ITEM_GUEST;
        return $array;
    }
    
    public function search($params,$array_product_id=false,$start_date=false,$end_date=false,$id=false,$status=false,$branch=false)
    {
        $query = InvoiceItem::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        $query->andFilterWhere(['<>', 'invoice_item.invoice_item_entity_id', NULL]);
        if($array_product_id){
            $query->andFilterWhere(['in', 'invoice_item.invoice_item_entity_id', $array_product_id]);
//            $query->andFilterWhere(['=', 'invoice_item.invoice_item_entity_id', $product_id]);
        }
        
        $query->select(['invoice_item.*','invoice.*']);
            $query->join("INNER JOIN",'invoice', "invoice_item.invoice_id=invoice.invoice_id");
            
        if($start_date){
            $query->andFilterWhere(['>=', 'date(invoice.invoice_date)', $start_date]);
        }
        
        if($end_date){
            $query->andFilterWhere(['<=', 'date(invoice.invoice_date)', $end_date]);
        }
        if($id){
            $query->andFilterWhere(['=', 'invoice_item.invoice_id', $id]);
        }
        if($status){
			if($status == 'All') {
				$query->andFilterWhere(['=', 'invoice.invoice_type', 'pos']);
			} else {
				$query->andFilterWhere(['like', 'invoice.invoice_status', $status])
					  ->andFilterWhere(['=', 'invoice.invoice_type', 'pos']);
			}
			$query->orderBy('invoice_item.invoice_id ASC');
        }
        if($branch){
            $query->andFilterWhere(['=', 'invoice.branch_id', $branch]);
        }
       
        return $dataProvider;
    }
    
    public function getItemByProductId($product_id,$start_date=false,$end_date=false){
		$query = new Query();
        $query->select('*')->from('invoice_item');
        $query->join("INNER JOIN",'invoice', "invoice_item.invoice_id=invoice.invoice_id");
		if($product_id)
             $query->andFilterWhere(['=', 'invoice_item.invoice_item_entity_id', $product_id]);
		 
		if($start_date)
        {
            $query->andFilterWhere(['>=', 'date(invoice.invoice_date)', $start_date]);
        }
        if($end_date)
        {
            $query->andFilterWhere(['<=', 'date(invoice.invoice_date)', $end_date]);
        }
		$rows = $query->all();

		$total = 0;
        $amount = 0;
		if(count($rows)){
			foreach($rows as $item){
				$total = $total + $item['invoice_item_quantity'];
				$amount = $amount + $item['invoice_item_amount'];
			}
		}
        $array = array();
        $array['total'] = $total;
        $array['amount'] = $amount;
        return $array;
    }
}
