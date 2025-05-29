<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

$this->title = 'Orders Management';
$this->params['breadcrumbs'][] = ['label' => 'Dashboard', 'url' => ['/admin/dashboard']];
$this->params['breadcrumbs'][] = $this->title;

$this->registerCss('
    .orders-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 25px;
        border-radius: 15px;
        margin-bottom: 30px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }
    .orders-table {
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
    .order-number {
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
');
?>

<div class="orders-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1><i class="fas fa-receipt"></i> Orders Management</h1>
            <p class="mb-0">Track and manage all customer orders</p>
        </div>
        <div>
            <?= Html::a('<i class="fas fa-download"></i> Export Orders', ['#'], ['class' => 'btn btn-light']) ?>
        </div>
    </div>
</div>

<div class="orders-table">
    <?php Pjax::begin(['id' => 'orders-pjax']); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => ['class' => 'table table-hover mb-0'],
        'layout' => '{items}{summary}{pager}',
        'columns' => [
            [
                'attribute' => 'order_number',
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::a(
                        Html::encode($model->order_number),
                        ['/admin/view-order', 'id' => $model->id],
                        ['class' => 'order-number text-decoration-none']
                    );
                },
                'contentOptions' => ['style' => 'font-weight: 500;']
            ],
            [
                'attribute' => 'user_id',
                'label' => 'Customer',
                'format' => 'raw',
                'value' => function ($model) {
                    return $model->user ? Html::encode($model->user->username) : 'Unknown User';
                }
            ],
            [
                'attribute' => 'total_amount',
                'format' => 'raw',
                'value' => function ($model) {
                    return '<strong>$' . number_format($model->total_amount, 2) . '</strong>';
                },
                'contentOptions' => ['class' => 'text-end']
            ],
            [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::tag('span', ucfirst($model->status), [
                        'class' => 'badge bg-' . $model->statusBadgeClass . ' status-badge'
                    ]);
                },
                'contentOptions' => ['class' => 'text-center']
            ],
            [
                'attribute' => 'payment_status',
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::tag('span', ucfirst($model->payment_status), [
                        'class' => 'badge bg-' . $model->paymentStatusBadgeClass . ' status-badge'
                    ]);
                },
                'contentOptions' => ['class' => 'text-center']
            ],
            [
                'attribute' => 'created_at',
                'format' => 'raw',
                'value' => function ($model) {
                    return '<div>' . date('M j, Y', strtotime($model->created_at)) . '</div>' .
                        '<small class="text-muted">' . date('g:i A', strtotime($model->created_at)) . '</small>';
                }
            ],
            [
                'label' => 'Actions',
                'format' => 'raw',
                'value' => function ($model) {
                    $buttons = [];

                    $buttons[] = Html::a('<i class="fas fa-eye"></i>', ['/admin/view-order', 'id' => $model->id], [
                        'class' => 'btn btn-sm btn-outline-primary',
                        'title' => 'View Order',
                        'data-bs-toggle' => 'tooltip'
                    ]);

                    if ($model->status !== 'delivered' && $model->status !== 'cancelled') {
                        $buttons[] = Html::a('<i class="fas fa-edit"></i>', ['/admin/update-order-status', 'id' => $model->id], [
                            'class' => 'btn btn-sm btn-outline-warning',
                            'title' => 'Update Status',
                            'data-bs-toggle' => 'tooltip'
                        ]);
                    }

                    return '<div class="action-buttons">' . implode(' ', $buttons) . '</div>';
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

<!-- Order Statistics -->
<div class="row mt-4">
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title text-warning">Pending</h5>
                <h3 class="text-warning"><?= \app\models\Order::find()->where(['status' => 'pending'])->count() ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title text-info">Processing</h5>
                <h3 class="text-info"><?= \app\models\Order::find()->where(['status' => 'processing'])->count() ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title text-primary">Shipped</h5>
                <h3 class="text-primary"><?= \app\models\Order::find()->where(['status' => 'shipped'])->count() ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title text-success">Delivered</h5>
                <h3 class="text-success"><?= \app\models\Order::find()->where(['status' => 'delivered'])->count() ?></h3>
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