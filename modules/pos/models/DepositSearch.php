<?php

namespace app\modules\pos\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\pos\models\Deposit;

/**
 * DepositSearch represents the model behind the search form about `app\modules\pos\models\Deposit`.
 */
class DepositSearch extends Deposit
{
    public $search_key;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['deposit_id', 'member_id', 'deposit_status'], 'integer'],
            [['deposit_no', 'deposit_name', 'deposit_phone', 'deposit_email', 'deposit_address', 'deposit_note'], 'safe'],
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
    public function search($params)
    {
        $query = Deposit::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        
        $query->orderBy(['deposit_no'=>SORT_ASC]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'deposit_id' => $this->deposit_id,
            'member_id' => $this->member_id,
            'deposit_status' => $this->deposit_status,
        ]);
        
        if($this->search_key){
            $query->join('LEFT JOIN', 'members','members.member_id=pos_deposit.member_id');
            $query->andWhere('deposit_name LIKE "%'.$this->search_key.'%" OR '
                    . 'REPLACE(CONCAT_WS(" ",members.surname,members.first_name),"Đ","d") LIKE "%'.$this->search_key.'%"'
                    . ' OR deposit_phone LIKE "%'.$this->search_key.'%" OR deposit_no LIKE "%'.$this->search_key.'%"'
                    . ' OR members.member_mobile LIKE "%'.$this->search_key.'%"');
        }

        $query->andFilterWhere(['like', 'deposit_no', $this->deposit_no])
            ->andFilterWhere(['like', 'deposit_phone', $this->deposit_phone])
            ->andFilterWhere(['like', 'deposit_email', $this->deposit_email])
            ->andFilterWhere(['like', 'deposit_address', $this->deposit_address])
            ->andFilterWhere(['like', 'deposit_note', $this->deposit_note]);

        return $dataProvider;
    }
}
