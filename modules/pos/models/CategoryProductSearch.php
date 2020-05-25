<?php

namespace app\modules\pos\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\pos\models\CategoryProduct;

/**
 * CategoryProductSearch represents the model behind the search form about `app\modules\pos\models\CategoryProduct`.
 */
class CategoryProductSearch extends CategoryProduct
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_product_id', 'category_product_created_by', 'category_product_parent', 'category_product_status'], 'integer'],
            [['category_product_name', 'category_product_description'], 'safe'],
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
        $query = CategoryProduct::find();

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
            'category_product_id' => $this->category_product_id,
            'category_product_created_by' => $this->category_product_created_by,
            'category_product_parent' => $this->category_product_parent,
            'category_product_status' => $this->category_product_status,
        ]);

        $query->andFilterWhere(['like', 'category_product_name', $this->category_product_name])
            ->andFilterWhere(['like', 'category_product_description', $this->category_product_description]);

        return $dataProvider;
    }
}
