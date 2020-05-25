<?php

namespace app\modules\pos\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\pos\models\Tables;

/**
 * TablesSearch represents the model behind the search form about `app\modules\pos\models\Tables`.
 */
class TablesSearch extends Tables
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['table_id', 'category_table_id', 'table_order', 'table_status', 'table_created_by'], 'integer'],
            [['table_name', 'table_created_date', 'table_description'], 'safe'],
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
        $query = Tables::find();

        // add conditions that should always apply here

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
            'table_id' => $this->table_id,
            'category_table_id' => $this->category_table_id,
            'table_order' => $this->table_order,
            'table_status' => $this->table_status,
            'table_created_by' => $this->table_created_by,
            'table_created_date' => $this->table_created_date,
        ]);

        $query->andFilterWhere(['like', 'table_name', $this->table_name])
            ->andFilterWhere(['like', 'table_description', $this->table_description]);

        return $dataProvider;
    }
}
