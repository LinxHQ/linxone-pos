<?php

namespace app\modules\pos\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\pos\models\Sesstion;

/**
 * SessionSearch represents the model behind the search form about `app\modules\pos\models\Sesstion`.
 */
class SessionSearch extends Sesstion
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'sesstion_start_date', 'sesstion_status'], 'required'],
            [['user_id','sesstion_status'], 'integer'],
            [['sesstion_start_date', 'sesstion_end_date'], 'safe'],
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
        $query = Sesstion::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'sort' => [
                'defaultOrder' => [
                    'sesstion_start_date'=>SORT_DESC,
                    'sesstion_end_date'=>SORT_DESC,
                ]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'user_id' => $this->user_id,
            'sesstion_start_date' => $this->sesstion_start_date,
            'sesstion_end_date' => $this->sesstion_end_date,
            'sesstion_status' => $this->sesstion_status,
        ]);
		
		$query->joinWith('user');

        return $dataProvider;
    }
}
