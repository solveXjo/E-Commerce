<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

$this->title = 'Products Management';
$this->params['breadcrumbs'][] = ['label' => 'Dashboard', 'url' => ['/admin/dashboard']];
$this->params['breadcrumbs'][] = $this->title;

$this->registerCss('
    .products-header {
        background: linear-gradient(135deg, #6B73FF 0%, #000DFF 100%);
        color: white;
        padding: 25px;
        border-radius: 15px;
        margin-bottom: 30px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }
    .products-table {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }
    .table thead th {
        background: linear-gradient(90deg, #f8f9fa 0%, #e9ecef 100%);
        border: none;
        font-weight: 600;
        color: #495057;
        padding: 15px;
    }
    .table tbody td {
        padding: 15px;
        border-color: #f1f3f4;
        vertical-align: middle;
    }
    .product-name {
        font-weight: bold;
        color: #007bff;
    }
    .status-badge {
        font-size: 0.85em;
        padding: 5px 10px;
        border-radius: 20px;
    }
    .action-buttons .btn {
        margin: 2px;
        padding: 5px 12px;
        font-size: 0.85em;
    }
    .product-image {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 8px;
        border: 1px solid #eee;
    }
');

?>

<div class="products-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1><i class="fas fa-box-open"></i> Products Management</h1>
            <p class="mb-0">Manage all products in your store</p>
        </div>
        <div>
            <?= Html::a('<i class="fas fa-plus"></i> Add Product', ['/admin/product-create'], ['class' => 'btn btn-light btn-lg']) ?>
        </div>
    </div>
</div>

<div class="products-table">
    <?php Pjax::begin(['id' => 'products-pjax']); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => ['class' => 'table table-hover mb-0'],
        'layout' => '{items}{summary}{pager}',
        'columns' => [
            [
                'attribute' => 'ImageURL',
                'format' => 'raw',
                'value' => function ($model) {
                    if ($model->ImageURL) {
                        return Html::img(Yii::getAlias('@web/') . $model->ImageURL, [
                            'alt' => $model->Name
                        ]);
                    }
                    return '<div class="product-image bg-light d-flex align-items-center justify-content-center">
                                <i class="fas fa-image text-muted"></i>
                            </div>';
                },
                'contentOptions' => ['style' => 'width: 80px;'],
                'filter' => false,
            ],
            [
                'attribute' => 'Name',
                'format' => 'raw',
                'value' => function ($model) {
                    return '<div class="product-name">' . Html::encode($model->Name) . '</div>';
                },
                'contentOptions' => ['style' => 'font-weight: 500;']
            ],
            [
                'attribute' => 'Category',
                'label' => 'Category',
                'value' => function ($model) {
                    return $model->Category ?: 'Uncategorized';
                }
            ],
            [
                'attribute' => 'Price',
                'format' => 'raw',
                'value' => function ($model) {
                    return '<strong>$' . number_format($model->Price, 2) . '</strong>';
                },
                'contentOptions' => ['class' => 'text-end']
            ],
            [
                'attribute' => 'StockQuantity',
                'format' => 'raw',
                'value' => function ($model) {
                    $class = $model->StockQuantity > 10 ? 'success' : ($model->StockQuantity > 0 ? 'warning' : 'danger');
                    return Html::tag('span', $model->StockQuantity, [
                        'class' => 'badge bg-' . $class . ' status-badge'
                    ]);
                },
                'contentOptions' => ['class' => 'text-center']
            ],
            [
                'attribute' => 'CreatedAt',
                'format' => 'raw',
                'value' => function ($model) {
                    return '<div>' . date('M j, Y', strtotime($model->CreatedAt)) . '</div>' .
                        '<small class="text-muted">' . date('g:i A', strtotime($model->CreatedAt)) . '</small>';
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => 'Actions',
                'template' => '{view} {update} {delete}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<i class="fas fa-eye"></i>', ['/admin/product-view', 'id' => $model->ProductID], [
                            'class' => 'btn btn-sm btn-outline-primary',
                            'title' => 'View',
                            'data-bs-toggle' => 'tooltip'
                        ]);
                    },
                    'update' => function ($url, $model) {
                        return Html::a('<i class="fas fa-edit"></i>', ['/admin/product-update', 'id' => $model->ProductID], [
                            'class' => 'btn btn-sm btn-outline-warning',
                            'title' => 'Update',
                            'data-bs-toggle' => 'tooltip'
                        ]);
                    },
                    'delete' => function ($url, $model) {
                        return Html::a('<i class="fas fa-trash"></i>', ['/admin/product-delete', 'id' => $model->ProductID], [
                            'class' => 'btn btn-sm btn-outline-danger',
                            'title' => 'Delete',
                            'data-bs-toggle' => 'tooltip',
                            'data-confirm' => 'Are you sure you want to delete this product?',
                            'data-method' => 'post',
                        ]);
                    },
                ],
                'contentOptions' => ['style' => 'width: 140px;']
            ],
        ],
        'pager' => [
            'options' => ['class' => 'pagination justify-content-center mt-4 mb-4'],
            'linkContainerOptions' => ['class' => 'page-item'],
            'linkOptions' => ['class' => 'page-link'],
            'disabledListItemSubTagOptions' => ['class' => 'page-link'],
        ]
    ]); ?>

    <?php Pjax::end(); ?>
</div>

<!-- Product Statistics -->
<div class="row mt-4">
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title text-primary">Total Products</h5>
                <h3 class="text-primary"><?= \app\models\Product::find()->count() ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title text-success">In Stock</h5>
                <h3 class="text-success"><?= \app\models\Product::find()->where(['>', 'StockQuantity', 0])->count() ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title text-warning">Low Stock</h5>
                <h3 class="text-warning"><?= \app\models\Product::find()->where(['between', 'StockQuantity', 1, 10])->count() ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title text-danger">Out of Stock</h5>
                <h3 class="text-danger"><?= \app\models\Product::find()->where(['StockQuantity' => 0])->count() ?></h3>
            </div>
        </div>
    </div>
</div>

<?php
$this->registerJs("
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle=\"tooltip\"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
");
?>