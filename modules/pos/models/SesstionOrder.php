<?php

namespace app\modules\pos\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\invoice\models\invoice;
use app\modules\invoice\models\InvoiceItem;

/**
 * This is the model class for table "pos_sesstion_order".
 *
 * @property integer $pos_sesstion_order_id
 * @property integer $sesstion_id
 * @property integer $invoice_id
 * @property integer $sesstion_id_old
 */
class SesstionOrder extends \yii\db\ActiveRecord
{
    public $invoice_no;
    public $invoice_date;
	
	/**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pos_sesstion_order';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sesstion_id', 'invoice_id'], 'required'],
            [['sesstion_id', 'invoice_id', 'sesstion_id_old'], 'integer'],
			[['invoice_no','invoice_date'],'safe']
			
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'pos_sesstion_order_id' => 'Pos Sesstion Order ID',
            'sesstion_id' => 'Sesstion ID',
            'invoice_id' => 'Invoice ID',
            'sesstion_id_old' => 'Sesstion Id Old',
        ];
    }
    
    public function getinvoice()
    {
        return $this->hasOne(invoice::className(), ['invoice_id' => 'invoice_id']);
    }
    
    public function search($params,$status=false,$sesstion_id=false)
    {
        $query = SesstionOrder::find();
		$query->join("LEFT JOIN",'invoice', "pos_sesstion_order.invoice_id=invoice.invoice_id");
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

//        if (!$this->validate()) {
//            // uncomment the following line if you do not want to return any records when validation fails
//            // $query->where('0=1');
//            return $dataProvider;
//        }
        // grid filtering conditions
        
        $query->andFilterWhere([
            'pos_sesstion_order_id' => $this->pos_sesstion_order_id,
            'sesstion_id' => $this->sesstion_id,
            'pos_sesstion_order.invoice_id' => $this->invoice_id,
            'sesstion_id_old' => $this->sesstion_id_old,
        ]);
        if($sesstion_id){
            $query->andFilterWhere(['=', 'sesstion_id', $sesstion_id]);
        }
        if($status){
                
            $query->andFilterWhere(['=', 'invoice.invoice_status', $status]);
				
        }
		$query->andFilterWhere(['like', 'invoice.invoice_no', $this->invoice_no]);
		$query->andFilterWhere(['like', 'invoice.invoice_date', $this->invoice_date]);
		
        return $dataProvider;
    }
    
    public function checkInvoiceSesstionOrder($invoice_id=false){
        $sesstion_order = \app\modules\pos\models\SesstionOrder::find()->where(['invoice_id'=>$invoice_id])->count();
        return $sesstion_order;
    }
    
    public function getTotalInvoicePaid(){
            $status = "Paid";
            $sesstion = new Sesstion();
            $sesstion_id = $sesstion->getSesstionIdNow();
            $dataProvider = $this->search(Yii::$app->request->queryParams, $status,$sesstion_id);
            $total = 0;
            if($dataProvider){
                foreach($dataProvider->models as $item){
                    $total = $total + $item->invoice->invoice_total_last_tax;
                }
                return $total;
            }
            return false;
    }
    
    public function getProductBySesstionId($params,$product){
        $query = InvoiceItem::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        
        $sesstion = new Sesstion();
        $sesstion_id = $sesstion->getSesstionIdNow();
        
        $query->select(['invoice.invoice_no', 'invoice.invoice_type_id','invoice.invoice_id','invoice_item.invoice_item_description','invoice_item.invoice_item_price', 'invoice_item.invoice_item_quantity', 'invoice_item.invoice_item_amount', 'invoice_item.invoice_item_tax']);
        $query->join("RIGHT JOIN",'pos_sesstion_order', "pos_sesstion_order.invoice_id=invoice_item.invoice_id");
        $query->andFilterWhere(['=', 'pos_sesstion_order.sesstion_id', $sesstion_id]);
        
        $query->join("LEFT JOIN",'invoice', "pos_sesstion_order.invoice_id=invoice.invoice_id");
        $query->andFilterWhere(['=', 'invoice.invoice_status', 'Paid']);
        
        if($product){
            $query->andFilterWhere(['=', 'invoice_item.invoice_item_description', $product]);
        }
        
        return $dataProvider;
    }
}
