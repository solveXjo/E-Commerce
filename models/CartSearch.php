<?php

namespace app\Models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Cart;

/**
 * CartSearch represents the model behind the search form of `app\models\Cart`.
 */
class CartSearch extends Cart
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['CartID', 'UserID'], 'integer'],
            [['CreatedAt', 'Status'], 'safe'],
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
        $query = Cart::find();

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
            'CartID' => $this->CartID,
            'UserID' => $this->UserID,
            'CreatedAt' => $this->CreatedAt,
        ]);

        $query->andFilterWhere(['like', 'Status', $this->Status]);

        return $dataProvider;
    }
}
