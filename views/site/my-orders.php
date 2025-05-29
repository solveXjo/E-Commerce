<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

$this->title = 'My Orders';
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
    .badge-pending {
        background-color: #ffc107;
        color: #212529;
    }
    .badge-processing {
        background-color: #17a2b8;
        color: white;
    }
    .badge-completed {
        background-color: #28a745;
        color: white;
    }
    .badge-cancelled {
        background-color: #dc3545;
        color: white;
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
            <h1><i class="fas fa-receipt"></i> My Orders</h1>
            <p class="mb-0">View your order history and status</p>
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
                'attribute' => 'id',
                'label' => 'Order #',
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::a(
                        '#' . Html::encode($model->id),
                        ['/site/view-order', 'id' => $model->id],
                        ['class' => 'order-number text-decoration-none']
                    );
                },
                'contentOptions' => ['style' => 'font-weight: 500;']
            ],
            [
                'attribute' => 'created_at',
                'format' => 'raw',
                'value' => function ($model) {
                    return '<div>' . Yii::$app->formatter->asDate($model->created_at, 'medium') . '</div>' .
                        '<small class="text-muted">' . Yii::$app->formatter->asTime($model->created_at, 'short') . '</small>';
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
                    $statusClass = 'badge-' . strtolower($model->status);
                    return Html::tag('span', ucfirst($model->status), [
                        'class' => 'badge ' . $statusClass . ' status-badge'
                    ]);
                },
                'contentOptions' => ['class' => 'text-center']
            ],
            [
                'label' => 'Shipping To',
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::encode($model->shipping_address) . '<br>' .
                        Html::encode($model->shipping_city) . ', ' .
                        Html::encode($model->shipping_postal_code);
                }
            ],
            [
                'label' => 'Actions',
                'format' => 'raw',
                'value' => function ($model) {
                    return '<div class="action-buttons">' .
                        Html::a('<i class="fas fa-eye"></i> View', ['/site/view-order', 'id' => $model->id], [
                            'class' => 'btn btn-sm btn-outline-primary',
                            'title' => 'View Order Details',
                            'data-bs-toggle' => 'tooltip'
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

<?php
$this->registerJs("
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle=\"tooltip\"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
");
?>