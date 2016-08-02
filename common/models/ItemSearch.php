<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Item;

/**
 * ItemSearch represents the model behind the search form about `common\models\Item`.
 */
class ItemSearch extends Item
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'item_slot', 'item_slot_spare', 'item_type_id', 'item_attack', 'item_defense', 'item_required_lvl', 'item_weapon_lvl'], 'integer'],
            [['source_id', 'item_name', 'item_num_hand', 'item_type', 'item_class', 'item_description'], 'safe'],
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
        $query = Item::find();

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
            'item_slot' => $this->item_slot,
            'item_slot_spare' => $this->item_slot_spare,
            'item_type_id' => $this->item_type_id,
            'item_attack' => $this->item_attack,
            'item_defense' => $this->item_defense,
            'item_required_lvl' => $this->item_required_lvl,
            'item_weapon_lvl' => $this->item_weapon_lvl,
        ]);

        $query->andFilterWhere(['like', 'source_id', $this->source_id])
            ->andFilterWhere(['like', 'item_name', $this->item_name])
            ->andFilterWhere(['like', 'item_num_hand', $this->item_num_hand])
            ->andFilterWhere(['like', 'item_type', $this->item_type])
            ->andFilterWhere(['like', 'item_class', $this->item_class])
            ->andFilterWhere(['like', 'item_description', $this->item_description]);

        return $dataProvider;
    }
}
