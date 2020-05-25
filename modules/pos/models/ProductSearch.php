<?php

namespace app\modules\pos\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\pos\models\Product;
use app\models\Config;

/**
 * ProductSearch represents the model behind the search form about `app\modules\pos\models\Product`.
 */
class ProductSearch extends Product
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'category_product_id', 'product_qty_out_of_stock', 'product_qty_notify', 'product_status'], 'integer'],
            [['product_no', 'product_name', 'product_description'], 'safe'],
            [['product_original', 'product_selling'], 'number'],
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
    public function search($params,$page=false, $sort=false, $category_array_id = false)
    {
        $query = Product::find();
        if($sort){
            $query->orderBy(['category_product_id'=> SORT_ASC]);
        }
        $pagination = ['defaultPageSize'=>'20'];
        if($page!==false){
            $config = new Config();
            if($config->getShowImgProduct() == 1){
                $query->limit = 12;
            }else{
                $query->limit = 24;
            }
            $query->offset = $page * $query->limit;
            $pagination = false;
        }
                
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => $pagination,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'product_id' => $this->product_id,
            'category_product_id' => $this->category_product_id,
            'product_original' => $this->product_original,
            'product_selling' => $this->product_selling,
            'product_qty_out_of_stock' => $this->product_qty_out_of_stock,
            'product_qty_notify' => $this->product_qty_notify,
            'product_status' => $this->product_status,
        ]);

        $query->andFilterWhere(['like', 'product_no', $this->product_no])
            ->andFilterWhere(['like', 'product_name', $this->product_name])
            ->andFilterWhere(['like', 'product_description', $this->product_description]);
        if($category_array_id && count($category_array_id)> 0){
            $query->andFilterWhere(['in', 'category_product_id', $category_array_id]);
        }
        
        return $dataProvider;
    }
}
