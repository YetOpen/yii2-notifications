<?php

namespace webzop\notifications\model;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use webzop\notifications\model\NotificationType;

/**
 * NotificationTypeSearch represents the model behind the search form of `webzop\notifications\model\NotificationType`.
 */
class NotificationTypeSearch extends NotificationType
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'check_management'], 'integer'],
            [['code', 'name', 'color', 'priority'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = NotificationType::find();

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
            'id' => $this->id,
            'check_management' => $this->check_management,
        ]);

        $query->andFilterWhere(['like', 'code', $this->code])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'color', $this->color])
            ->andFilterWhere(['like', 'priority', $this->priority]);

        return $dataProvider;
    }
}
