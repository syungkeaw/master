<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\ShopItem;
use common\classes\RoHelper;

/**
 * ShopItemSearch represents the model behind the search form about `common\models\ShopItem`.
 */
class ShopItemSearch extends ShopItem
{
    
    public function attributes()
    {
        // add related fields to searchable attributes
        return array_merge(parent::attributes(), ['item.item_name', 'shop.shop_name', 'shop.character', 'shop.server', 'shop.status', 'option']);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'shop_id', 'price', 'amount', 'created_at', 'updated_at', 'enhancement', 'shop.server', 'shop.status'], 'integer'],
            [['item_id', 'item.item_name', 'shop.shop_name', 'shop.character', 'option'], 'string'],
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
        $query = ShopItem::find();

        $query->joinWith(['item']);
        $query->joinWith(['shop']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => [
                   'item.item_name', 
                   'shop.shop_name',
                   'shop.character',
                   'shop.server',
                   'shop.status',
                   'price', 
                   'amount', 
                   'updated_at', 
                   'enhancement', 
                ],
                'defaultOrder' => ['updated_at' => SORT_DESC],
            ],
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
            'item_id' => $this->item_id,
            'shop_id' => $this->shop_id,
            'price' => $this->price,
            'amount' => $this->amount,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'item.item_name', $this->getAttribute('item.item_name')]);
        $query->andFilterWhere(['like', 'shop.shop_name', $this->getAttribute('shop.shop_name')]);
        $query->andFilterWhere(['like', 'shop.character', $this->getAttribute('shop.character')]);

        $query->andFilterWhere(['shop.server' => $this->getAttribute('shop.server')]);

        if($this->getAttribute('shop.status') == 0){
            $query->andFilterWhere(
                ['or', 
                    ['shop.status' => $this->getAttribute('shop.status')],
                    ['shop_item.status' => $this->getAttribute('shop.status')]
            ]);
        }else if($this->getAttribute('shop.status') == 10){
            $query->andFilterWhere(['shop.status' => $this->getAttribute('shop.status')]);
            $query->andFilterWhere(['shop_item.status' => $this->getAttribute('shop.status')]);
        }
        

        // if(preg_match('/\\[\\d\\]/', $this->getAttribute('item.item_name'), $matches)){
        //     $query->andFilterWhere(['item.item_slot' => str_replace(['[', ']'], '', $matches[0])]);
        // }
        if($this->getAttribute('enhancement') != 'null'){
            $query->andFilterWhere(['enhancement' => $this->enhancement]);
        }

        if(in_array($this->getAttribute('option'), ['994', '995', '996', '997'])){
            $query->andFilterWhere(['element' => ($this->getAttribute('option') - 993)]);
        }else{
            $query->andFilterWhere(
                ['or', 
                    ['or', ['card_1' => $this->getAttribute('option')], ['card_2' => $this->getAttribute('option')]],
                    ['or', ['card_3' => $this->getAttribute('option')], ['card_4' => $this->getAttribute('option')]],
                ]
            );
        }


        return $dataProvider;
    }
}
