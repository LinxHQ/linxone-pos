<?php

namespace app\modules\invoice\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\invoice\models\invoice;

/**
 * InvoiceSearch represents the model behind the search form about `app\modules\invoice\models\invoice`.
 */
class InvoiceSearch extends invoice
{
	public $member_ids = array();
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['invoice_id', 'invoice_type_id', 'member_id', 'invoice_discount', 'invoice_gst', 'invoice_status'], 'integer'],
            [['invoice_no', 'invoice_date', 'invoice_note', 'invoice_type'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params,$start_date=false,$end_date=false,$table_id=false,$date= false,$sesstion=false,$status=false,$member_id=false,$invoice_type=false)
    {
        $query = invoice::find();

        // add conditions that should always apply here
        $query->orderBy(['invoice_date' => SORT_ASC]);
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
            'invoice_id' => $this->invoice_id,
            'invoice_date' => $this->invoice_date,
            'invoice_type_id' => $this->invoice_type_id,
            'member_id' => $this->member_id,
            'invoice_discount' => $this->invoice_discount,
            'invoice_gst' => $this->invoice_gst
        ]);
//        echo $this->invoice_status.'dsdsd';
        if($start_date && $start_date != '')
        {

            $query->andFilterWhere(['>=', 'date(invoice_date)', $start_date]);
        }
        if($end_date && $end_date != '')
        {

            $query->andFilterWhere(['<=', 'date(invoice_date)', $end_date]);
        }
        if($table_id){
            $query->andFilterWhere(['=', 'invoice_type', 'pos'])
                ->andFilterWhere(['=', 'invoice_type_id', $table_id])
                ->andFilterWhere(['=', 'date(invoice_date)', date('Y-m-d')])
                ->andFilterWhere(['=', 'invoice_status', 'Outstanding']);
        }
        if($date){
            $query->andFilterWhere(['=', 'date(invoice_date)', date('Y-m-d')]);
        }
        $query->andFilterWhere(['like', 'invoice_no', $this->invoice_no])
            ->andFilterWhere(['like', 'invoice_note', $this->invoice_note])
            ->andFilterWhere(['like', 'invoice_type', $this->invoice_type])
            ->andFilterWhere(['like', 'invoice_status', $this->invoice_status]);
        
        $query->andFilterWhere(['<>', 'invoice_status', self::INVOICE_STATUS_VOID_INVOICE]);
        
        if($sesstion){
            $query->andFilterWhere(['=', 'invoice_status', 'Paid']);
            $query->join("LEFT JOIN",'pos_sesstion_order', "invoice.invoice_id = pos_sesstion_order.invoice_id");
            $query->andFilterWhere(['=', 'pos_sesstion_order.sesstion_id', $sesstion]);
        }
        if($status){
            $query->andFilterWhere(['like', 'invoice_status', $status]);
        }
        if($member_id){
            $query->andFilterWhere(['=', 'member_id', $member_id]);
        }
		if($this->member_ids && count($this->member_ids) >0)
            $query->andFilterWhere (['in','member_id',$this->member_ids]);
        if($invoice_type){
            $query->andFilterWhere(['=', 'invoice_type', $invoice_type]);
        }
        return $dataProvider;
    }
}
