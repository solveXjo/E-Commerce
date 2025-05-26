<?php

use app\models\CartItem;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\Models\CartItemSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Cart Items';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cart-item-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Cart Item', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'CartItemID',
            'CartID',
            'ProductID',
            'Quantity',
            'Price',
            //'AddedAt',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, CartItem $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'CartItemID' => $model->CartItemID]);
                 }
            ],
        ],
    ]); ?>


</div>
