<?php

namespace app\Models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\CartItem;

/**
 * CartItemSearch represents the model behind the search form of `app\models\CartItem`.
 */
class CartItemSearch extends CartItem
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['CartItemID', 'CartID', 'ProductID', 'Quantity'], 'integer'],
            [['Price'], 'number'],
            [['AddedAt'], 'safe'],
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
     * @param string|null $formName Form name to be used into `->load()` method.
     *
     * @return ActiveDataProvider
     */
    public function search($params, $formName = null)
    {
        $query = CartItem::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params, $formName);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'CartItemID' => $this->CartItemID,
            'CartID' => $this->CartID,
            'ProductID' => $this->ProductID,
            'Quantity' => $this->Quantity,
            'Price' => $this->Price,
            'AddedAt' => $this->AddedAt,
        ]);

        return $dataProvider;
    }
}
