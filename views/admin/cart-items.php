<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

$this->title = 'Cart Items Management';
$this->params['breadcrumbs'][] = ['label' => 'Dashboard', 'url' => ['/admin/dashboard']];
$this->params['breadcrumbs'][] = $this->title;

$this->registerCss('
    .cartitems-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 25px;
        border-radius: 15px;
        margin-bottom: 30px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }
    .cartitems-table {
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
    .action-buttons .btn {
        margin: 2px;
        padding: 5px 12px;
        font-size: 0.85em;
    }
');
?>

<div class="cartitems-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1><i class="fas fa-shopping-cart"></i> Cart Items Management</h1>
            <p class="mb-0">View and manage all cart items in the system</p>
        </div>
        <div>
            <?= Html::a('<i class="fas fa-download"></i> Export Cart Items', ['#'], ['class' => 'btn btn-light']) ?>
        </div>
    </div>
</div>

<div class="cartitems-table">
    <?php Pjax::begin(['id' => 'cartitems-pjax']); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => ['class' => 'table table-hover mb-0'],
        'layout' => '{items}{summary}{pager}',
        'columns' => [
            [
                'attribute' => 'CartItemID',
                'label' => 'ID',
                'contentOptions' => ['style' => 'font-weight: 500;']
            ],
            [
                'attribute' => 'product.Name',
                'label' => 'Product',
                'format' => 'raw',
                'value' => function ($model) {
                    return $model->product ?
                        Html::a(
                            Html::encode($model->product->Name),
                            ['/admin/product-view', 'id' => $model->product->ProductID],
                            ['class' => 'product-name text-decoration-none']
                        ) : 'Product Deleted';
                }
            ],
            [
                'attribute' => 'cart.user.username',
                'label' => 'User',
                'format' => 'raw',
                'value' => function ($model) {
                    return $model->cart && $model->cart->user ?
                        Html::a(
                            Html::encode($model->cart->user->username),
                            ['/admin/users'],
                            ['class' => 'text-decoration-none']
                        ) : 'Guest User';
                }
            ],
            [
                'attribute' => 'Quantity',
                'contentOptions' => ['class' => 'text-center']
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
                'attribute' => 'AddedAt',
                'format' => 'datetime',
                'contentOptions' => ['class' => 'text-center']
            ],
            [
                'label' => 'Actions',
                'format' => 'raw',
                'value' => function ($model) {
                    return '<div class="action-buttons">' .
                        Html::a('<i class="fas fa-eye"></i>', ['/admin/product-view', 'id' => $model->ProductID], [
                            'class' => 'btn btn-sm btn-outline-primary',
                            'title' => 'View Product',
                            'data-bs-toggle' => 'tooltip'
                        ]) .
                        Html::a('<i class="fas fa-trash"></i>', ['#'], [
                            'class' => 'btn btn-sm btn-outline-danger',
                            'title' => 'Remove Item',
                            'data-bs-toggle' => 'tooltip',
                            'data' => [
                                'confirm' => 'Are you sure you want to delete this cart item?',
                                'method' => 'post',
                            ],
                        ]) .
                        '</div>';
                },
                'contentOptions' => ['style' => 'width: 120px;']
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

<!-- Cart Statistics -->
<div class="row mt-4">
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title text-primary">Total Cart Items</h5>
                <h3 class="text-primary"><?= \app\models\CartItem::find()->count() ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title text-info">Active Carts</h5>
                <h3 class="text-info"><?= \app\models\Cart::find()->count() ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title text-success">Potential Revenue</h5>
                <h3 class="text-success">$<?= number_format(\app\models\CartItem::find()->sum('Price * Quantity'), 2) ?></h3>
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