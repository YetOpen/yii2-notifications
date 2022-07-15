<?php

namespace webzop\notifications\model;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use webzop\notifications\model\Notifications;

/**
 * NotificationSearch represents the model behind the search form of `webzop\notifications\model\Notifications`.
 */
class NotificationSearch extends Notifications
{
    /**
     * @var string
     */
    public $created_at_filter;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'seen','read', 'user_id', 'sent', 'created_at', 'managed'], 'integer'],
            [['class', 'type', 'seen', 'key', 'channel', 'message', 'content', 'attachments', 'language', 'route', 'send_at', 'managed', 'created_at_filter'], 'safe'],
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
        $query = Notifications::find();
        $query -> joinWith('notificationsType');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $dataProvider->sort->attributes['check_management']=[
            'asc' => ['check_management' => SORT_ASC],
            'desc' => ['check_management' => SORT_DESC],
            'default' => SORT_DESC,
        ];

        $dataProvider->sort->attributes['priority']=[
            'asc' => ['priority' => SORT_ASC],
            'desc' => ['priority' => SORT_DESC],
            'default' => SORT_DESC,
        ];

        $dataProvider->sort->defaultOrder= [
                'read' => SORT_ASC,
                'check_management' => SORT_DESC,
                'managed' => SORT_ASC,
                'priority' => SORT_ASC,
            ]
        ;

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'type' => $this->type,
            'seen' => $this->seen,
            'read' => $this->read,
            'user_id' => $this->user_id,
            'send_at' => $this->send_at,
            'sent' => $this->sent,
            'created_at' => $this->created_at,
            'managed' => $this->managed,
        ]);

        $query->andFilterWhere(['like', 'class', $this->class])
            ->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'key', $this->key])
            ->andFilterWhere(['like', 'channel', $this->channel])
            ->andFilterWhere(['like', 'message', $this->message])
            ->andFilterWhere(['like', 'content', $this->content])
            ->andFilterWhere(['like', 'attachments', $this->attachments])
            ->andFilterWhere(['like', 'language', $this->language])
            ->andFilterWhere(['like', 'route', $this->route]);


        //filter datetime range from string to integer
        if(isset ($this->created_at_filter) && $this->created_at_filter != '' && strpos($this->created_at_filter, ' - ') !== false){
            $date_explode = explode(" - ", $this->created_at_filter);
            $date1 = date_timestamp_get(date_create_from_format('d/m/Y H:i', trim($date_explode[0])));
            $date2 = date_timestamp_get(date_create_from_format('d/m/Y H:i', trim($date_explode[1])));
            $query->andFilterWhere(['between','created_at',"$date1" , "$date2"]);
        }

        return $dataProvider;
    }
}
