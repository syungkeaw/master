<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Shop;
use common\classes\RoHelper;

/**
 * ShopSearch represents the model behind the search form about `common\models\Shop`.
 */
class ShopSearch extends Shop
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'map', 'not_found_count', 'status', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'integer'],
            [['shop_name', 'location', 'character'], 'safe'],
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
        $query = Shop::find();

        $query->where(['created_by' => Yii::$app->user->identity->id]);
        
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

        $query->andFilterWhere([
            'server' => RoHelper::getActiveServerId(),
        ]);

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'map' => $this->map,
            'not_found_count' => $this->not_found_count,
            'status' => $this->status,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_by' => $this->updated_by,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'shop_name', $this->shop_name])
            ->andFilterWhere(['like', 'location', $this->location])
            ->andFilterWhere(['like', 'character', $this->character]);

        return $dataProvider;
    }
}
