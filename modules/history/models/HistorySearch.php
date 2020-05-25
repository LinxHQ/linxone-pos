<?php

namespace app\modules\history\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\history\models\History;

/**
 * HistorySearch represents the model behind the search form about `app\modules\history\models\History`.
 */
class HistorySearch extends History
{
    public $username;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['history_id', 'history_user'], 'integer'],
            [['history_action', 'history_date', 'history_item', 'history_table', 'history_module', 'history_description', 'history_content','username'], 'safe'],
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
        $query = History::find();

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
            'history_id' => $this->history_id,
            'history_user' => $this->history_user,
            'history_date' => $this->history_date,
        ]);
        
        $query->joinWith('user');

        $query->andFilterWhere(['like', 'history_action', $this->history_action])
            ->andFilterWhere(['=', 'history_item', $this->history_item])
            ->andFilterWhere(['like', 'history_table', $this->history_table])
            ->andFilterWhere(['like', 'history_module', $this->history_module])
            ->andFilterWhere(['like', 'history_description', $this->history_description])
            ->andFilterWhere(['like', 'history_content', $this->history_content])
            ->andFilterWhere(['like', 'user.username', $this->username]);

        return $dataProvider;
    }
}
