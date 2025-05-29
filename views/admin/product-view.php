<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = $model->Name;
$this->params['breadcrumbs'][] = ['label' => 'Dashboard', 'url' => ['/admin/dashboard']];
$this->params['breadcrumbs'][] = ['label' => 'Products', 'url' => ['/admin/products']];
$this->params['breadcrumbs'][] = $this->title;

$this->registerCss('
    .view-header {
        background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        color: white;
        padding: 25px;
        border-radius: 15px;
        margin-bottom: 30px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }
    .product-details {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }
    .product-image-container {
        background: white;
        border-radius: 15px;
        padding: 20px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        text-align: center;
    }
    .product-image {
        max-width: 100%;
        max-height: 400px;
        border-radius: 10px;
        border: 1px solid #e9ecef;
    }
    .no-image {
        height: 300px;
        background: #f8f9fa;
        border: 2px dashed #dee2e6;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6c757d;
        flex-direction: column;
    }
    .table th {
        background: #f8f9fa;
        font-weight: 600;
        color: #495057;
        border: none;
        padding: 15px;
    }
    .table td {
        padding: 15px;
        border-color: #f1f3f4;
    }
    .status-badge {
        font-size: 0.9em;
        padding: 8px 15px;
        border-radius: 20px;
    }
    .action-buttons .btn {
        margin: 5px;
        padding: 12px 25px;
        font-weight: 600;
    }
');

?>

<div class="view-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1><i class="fas fa-box"></i> <?= Html::encode($this->title) ?></h1>
            <p class="mb-0">Product Details and Information</p>
        </div>
        <div class="action-buttons">
            <?= Html::a('<i class="fas fa-edit"></i> Edit', ['/admin/product-update', 'id' => $model->ProductID], ['class' => 'btn btn-warning']) ?>
            <?= Html::a('<i class="fas fa-trash"></i> Delete', ['/admin/product-delete', 'id' => $model->ProductID], [
                'class' => 'btn btn-danger',
                'data-confirm' => 'Are you sure you want to delete this product?',
                'data-method' => 'post',
            ]) ?>
            <?= Html::a('<i class="fas fa-arrow-left"></i> Back to Products', ['/admin/products'], ['class' => 'btn btn-secondary']) ?>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="product-image-container">
            <h5 class="mb-3"><i class="fas fa-image"></i> Product Image</h5>
            <?php if ($model->ImageURL): ?>
                <?= Html::img(Yii::getAlias('@web/') . $model->ImageURL, [
                    'class' => 'product-image',
                    'alt' => $model->Name
                ]) ?>
            <?php else: ?>
                <div class="no-image">
                    <i class="fas fa-image fa-3x mb-3"></i>
                    <p>No image available</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="col-md-8">
        <div class="product-details">
            <?= DetailView::widget([
                'model' => $model,
                'options' => ['class' => 'table table-striped table-bordered detail-view mb-0'],
                'attributes' => [
                    'ProductID',
                    'Name:text',
                    [
                        'attribute' => 'Description',
                        'format' => 'ntext',
                        'value' => $model->Description ?: 'No description available',
                    ],
                    [
                        'attribute' => 'Price',
                        'format' => 'raw',
                        'value' => '<strong style="font-size: 1.2em; color: #28a745;">$' . number_format($model->Price, 2) . '</strong>',
                    ],
                    [
                        'attribute' => 'StockQuantity',
                        'format' => 'raw',
                        'value' => function ($model) {
                            if ($model->StockQuantity > 10) {
                                $class = 'success';
                                $text = 'In Stock';
                            } elseif ($model->StockQuantity > 0) {
                                $class = 'warning';
                                $text = 'Low Stock';
                            } else {
                                $class = 'danger';
                                $text = 'Out of Stock';
                            }
                            return Html::tag('span', $model->StockQuantity . ' units', [
                                'class' => 'badge bg-' . $class . ' status-badge me-2'
                            ]) . Html::tag('small', '(' . $text . ')', ['class' => 'text-muted']);
                        },
                    ],
                    [
                        'attribute' => 'Category',
                        'label' => 'Category',
                        'value' => $model->Category ?: 'Uncategorized',
                    ],
                    [
                        'attribute' => 'CreatedAt',
                        'format' => 'raw',
                        'value' => date('F j, Y g:i A', strtotime($model->CreatedAt)),
                    ],
                    [
                        'attribute' => 'UpdatedAt',
                        'format' => 'raw',
                        'value' => $model->UpdatedAt ? date('F j, Y g:i A', strtotime($model->UpdatedAt)) : 'Not updated',
                    ],
                ],
            ]) ?>
        </div>
    </div>
</div>

<!-- Additional Product Statistics -->
<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-chart-bar"></i> Product Statistics</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-3">
                        <div class="border-end">
                            <h4 class="text-primary"><?= $model->StockQuantity ?></h4>
                            <p class="text-muted mb-0">Current Stock</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border-end">
                            <h4 class="text-success">$<?= number_format($model->Price, 2) ?></h4>
                            <p class="text-muted mb-0">Unit Price</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border-end">
                            <h4 class="text-info">$<?= number_format($model->Price * $model->StockQuantity, 2) ?></h4>
                            <p class="text-muted mb-0">Total Value</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <h4 class="text-warning"><?= date('M Y', strtotime($model->CreatedAt)) ?></h4>
                        <p class="text-muted mb-0">Added</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>