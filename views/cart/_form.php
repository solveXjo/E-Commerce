<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Cart $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="cart-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'UserID')->textInput() ?>

    <!-- <?= $form->field($model, 'CreatedAt')->textInput() ?> -->

    <?= $form->field($model, 'Status')->dropDownList(['open' => 'Open', 'checked_out' => 'Checked out',], ['prompt' => '']) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>