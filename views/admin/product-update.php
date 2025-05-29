<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Update Product: ' . $model->Name;
$this->params['breadcrumbs'][] = ['label' => 'Dashboard', 'url' => ['/admin/dashboard']];
$this->params['breadcrumbs'][] = ['label' => 'Products', 'url' => ['/admin/products']];
$this->params['breadcrumbs'][] = ['label' => $model->Name, 'url' => ['/admin/product-view', 'id' => $model->ProductID]];
$this->params['breadcrumbs'][] = 'Update';

$this->registerCss('
    .update-header {
        background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
        color: white;
        padding: 25px;
        border-radius: 15px;
        margin-bottom: 30px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }
    .form-container {
        background: white;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }
    .form-group {
        margin-bottom: 20px;
    }
    .form-control, .form-select {
        border-radius: 8px;
        border: 2px solid #e9ecef;
        padding: 12px 15px;
        transition: all 0.3s ease;
    }
    .form-control:focus, .form-select:focus {
        border-color: #ffc107;
        box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
    }
    .btn-warning {
        padding: 12px 30px;
        border-radius: 8px;
        font-weight: 600;
    }
    .btn-secondary {
        padding: 12px 30px;
        border-radius: 8px;
    }
    .current-image {
        max-height: 200px;
        border-radius: 8px;
        border: 1px solid #ddd;
    }
');

?>

<div class="update-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1><i class="fas fa-edit"></i> Update Product</h1>
            <p class="mb-0">Modify product details: <?= Html::encode($model->Name) ?></p>
        </div>
        <div>
            <?= Html::a('<i class="fas fa-arrow-left"></i> Back to Products', ['/admin/products'], ['class' => 'btn btn-light']) ?>
        </div>
    </div>
</div>

<div class="form-container">
    <?php $form = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data']
    ]); ?>

    <div class="row">
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-12">
                    <?= $form->field($model, 'Name')->textInput([
                        'maxlength' => true,
                        'placeholder' => 'Enter product name'
                    ]) ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'Price')->textInput([
                        'type' => 'number',
                        'step' => '0.01',
                        'min' => '0',
                        'placeholder' => '0.00'
                    ]) ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'StockQuantity')->textInput([
                        'type' => 'number',
                        'min' => '0',
                        'placeholder' => '0'
                    ]) ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <?= $form->field($model, 'Category')->textInput([
                        'placeholder' => 'Enter category'
                    ]) ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <?= $form->field($model, 'Description')->textarea([
                        'rows' => 6,
                        'placeholder' => 'Enter product description...'
                    ]) ?>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-image"></i> Product Image</h5>
                </div>
                <div class="card-body text-center">
                    <?php if ($model->ImageURL): ?>
                        <div class="mb-3">
                            <p><strong>Current Image:</strong></p>
                            <?= Html::img(Yii::getAlias('@web/') . $model->ImageURL, [
                                'class' => 'current-image img-fluid',
                                'alt' => $model->Name
                            ]) ?>
                        </div>
                    <?php endif; ?>

                    <?= $form->field($model, 'ImageURL')->fileInput([
                        'accept' => 'image/*',
                        'class' => 'form-control'
                    ])->label('Upload New Image (Optional)') ?>

                    <div id="image-preview" class="mt-3" style="display: none;">
                        <p><strong>New Image Preview:</strong></p>
                        <img id="preview-img" src="" alt="Preview" class="img-fluid rounded" style="max-height: 200px;">
                    </div>

                    <small class="text-muted">Supported formats: JPG, PNG, GIF<br>Leave empty to keep current image</small>
                </div>
            </div>
        </div>
    </div>

    <hr class="my-4">

    <div class="form-group d-flex justify-content-between">
        <?= Html::submitButton('<i class="fas fa-save"></i> Update Product', ['class' => 'btn btn-warning btn-lg']) ?>
        <div>
            <?= Html::a('<i class="fas fa-eye"></i> View Product', ['/admin/product-view', 'id' => $model->ProductID], ['class' => 'btn btn-info btn-lg me-2']) ?>
            <?= Html::a('<i class="fas fa-times"></i> Cancel', ['/admin/products'], ['class' => 'btn btn-secondary btn-lg']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<?php
$this->registerJs("
    // Image preview
    document.getElementById('product-ImageURL').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('preview-img').src = e.target.result;
                document.getElementById('image-preview').style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            document.getElementById('image-preview').style.display = 'none';
        }
    });
");
?>