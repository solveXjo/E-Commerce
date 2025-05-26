<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\Models\CartItemSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="cart-item-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'CartItemID') ?>

    <?= $form->field($model, 'CartID') ?>

    <?= $form->field($model, 'ProductID') ?>

    <?= $form->field($model, 'Quantity') ?>

    <?= $form->field($model, 'Price') ?>

    <?php // echo $form->field($model, 'AddedAt') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
