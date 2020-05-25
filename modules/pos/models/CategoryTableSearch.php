<?php

namespace app\modules\pos\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\pos\models\CategoryTable;

/**
 * CategoryTableSearch represents the model behind the search form about `app\modules\pos\models\CategoryTable`.
 */
class CategoryTableSearch extends CategoryTable
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_table_id', 'category_table_create_by', 'category_table_parent', 'category_table_status'], 'integer'],
            [['category_table_name', 'category_table_description'], 'safe'],
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
        $query = CategoryTable::find();

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
            'category_table_id' => $this->category_table_id,
            'category_table_create_by' => $this->category_table_create_by,
            'category_table_parent' => $this->category_table_parent,
            'category_table_status' => $this->category_table_status,
        ]);
        
        $query->orderBy(['category_table_id'=>SORT_DESC]);

        $query->andFilterWhere(['like', 'category_table_name', $this->category_table_name])
            ->andFilterWhere(['like', 'category_table_description', $this->category_table_description]);

        return $dataProvider;
    }
}
