<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Cart $model */

$this->title = 'Update Cart: ' . $model->CartID;
$this->params['breadcrumbs'][] = ['label' => 'Carts', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->CartID, 'url' => ['view', 'CartID' => $model->CartID]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="cart-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
