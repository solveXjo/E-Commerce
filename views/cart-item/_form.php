<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\CartItem $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="cart-item-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'CartID')->textInput() ?>

    <?= $form->field($model, 'ProductID')->textInput() ?>

    <?= $form->field($model, 'Quantity')->textInput() ?>

    <?= $form->field($model, 'Price')->textInput(['maxlength' => true]) ?>

    <!-- <?= $form->field($model, 'AddedAt')->textInput() ?> -->

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
