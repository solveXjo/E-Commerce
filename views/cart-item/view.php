<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\CartItem $model */

$this->title = $model->CartItemID;
$this->params['breadcrumbs'][] = ['label' => 'Cart Items', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="cart-item-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'CartItemID' => $model->CartItemID], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'CartItemID' => $model->CartItemID], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'CartItemID',
            'CartID',
            'ProductID',
            'Quantity',
            'Price',
            'AddedAt',
        ],
    ]) ?>

</div>
