<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\Models\ProductSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="product-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'ProductID') ?>

    <?= $form->field($model, 'Name') ?>

    <?= $form->field($model, 'Price') ?>

    <?= $form->field($model, 'Description') ?>

    <?= $form->field($model, 'Category') ?>

    <?php // echo $form->field($model, 'StockQuantity') ?>

    <?php // echo $form->field($model, 'ImageURL') ?>

    <?php // echo $form->field($model, 'CreatedAt') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
