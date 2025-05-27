<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Product $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="product-form">
    <?php $form = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data']
    ]); ?>

    <?= $form->field($model, 'Name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'Price')->textInput(['type' => 'number', 'step' => '0.01']) ?>
    <?= $form->field($model, 'Description')->textarea(['rows' => 6]) ?>
    <?= $form->field($model, 'Category')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'StockQuantity')->textInput(['type' => 'number']) ?>
    <?= $form->field($model, 'eventImage')->fileInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>