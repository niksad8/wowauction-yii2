<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ScanStats;

/**
 * ScanStatsSearch represents the model behind the search form of `app\models\ScanStats`.
 */
class ScanStatsSearch extends ScanStats
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'realm_id', 'faction_id', 'total_items', 'total_listed', 'cnt_prices_increased', 'cnt_prices_decreased', 'total_amt_bid', 'total_buyout_gold'], 'integer'],
            [['datetime'], 'safe'],
            [['avg_price_change', 'total_bid_gold'], 'number'],
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
        $query = ScanStats::find();

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
            'realm_id' => $this->realm_id,
            'faction_id' => $this->faction_id,
            'datetime' => $this->datetime,
            'total_items' => $this->total_items,
            'total_listed' => $this->total_listed,
            'cnt_prices_increased' => $this->cnt_prices_increased,
            'cnt_prices_decreased' => $this->cnt_prices_decreased,
            'total_amt_bid' => $this->total_amt_bid,
            'avg_price_change' => $this->avg_price_change,
            'total_bid_gold' => $this->total_bid_gold,
            'total_buyout_gold' => $this->total_buyout_gold,
        ]);

        return $dataProvider;
    }
}
