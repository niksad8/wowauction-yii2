<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ItemPrices;

/**
 * ItemPricesSearch represents the model behind the search form of `app\models\ItemPrices`.
 */
class ItemPricesSearch extends ItemPrices
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['quantity', 'realm_id', 'faction_id'], 'integer'],
            [['itemid'], 'safe'],
            [['bid_mean', 'bid_median', 'cost_price', 'bid_median_last_compare', 'bid_mean_last_compare', 'buyout_mean', 'buyout_median', 'buyout_median_last_compare', 'buyout_mean_last_compare'], 'number'],
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
        $query = ItemPrices::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination'=>[
                'pageSize'=>50
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->joinWith('item');
        // grid filtering conditions
        $query->andFilterWhere([
            'datetime' => $this->datetime,
            'bid_mean' => $this->bid_mean,
            'bid_median' => $this->bid_median,
            'cost_price' => $this->cost_price,
            'quantity' => $this->quantity,
            'faction_id'=>$this->faction_id,
            'realm_id'=>$this->realm_id,
            'bid_median_last_compare' => $this->bid_median_last_compare,
            'bid_mean_last_compare' => $this->bid_mean_last_compare,
            'buyout_mean' => $this->buyout_mean,
            'buyout_median' => $this->buyout_median,
            'buyout_median_last_compare' => $this->buyout_median_last_compare,
            'buyout_mean_last_compare' => $this->buyout_mean_last_compare,
        ]);
        $query->andFilterWhere(['like','item_template.name',$this->itemid]);
        return $dataProvider;
    }
}
