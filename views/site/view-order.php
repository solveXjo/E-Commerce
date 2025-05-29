<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = 'Order #' . $order->id;
$this->params['breadcrumbs'][] = ['label' => 'My Orders', 'url' => ['my-orders']];
$this->params['breadcrumbs'][] = $this->title;

$this->registerCss('
    .order-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 25px;
        border-radius: 15px;
        margin-bottom: 30px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }
    .order-details {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        margin-bottom: 30px;
    }
    .order-items {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }
    .badge-status {
        font-size: 1em;
        padding: 8px 15px;
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
    .table-order-items {
        width: 100%;
    }
    .table-order-items th {
        background: #f8f9fa;
        padding: 15px;
        text-align: left;
    }
    .table-order-items td {
        padding: 15px;
        border-bottom: 1px solid #f1f3f4;
    }
    .table-order-items tr:last-child td {
        border-bottom: none;
    }
');

$statusClass = 'badge-' . strtolower($order->status);
?>

<div class="order-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1><i class="fas fa-receipt"></i> Order #<?= $order->id ?></h1>
            <p class="mb-0">
                <span class="badge <?= $statusClass ?> badge-status"><?= ucfirst($order->status) ?></span>
                <span class="ms-2">Placed on <?= Yii::$app->formatter->asDatetime($order->created_at) ?></span>
            </p>
        </div>
        <div>
            <?= Html::a('<i class="fas fa-print"></i> Print Invoice', ['#'], ['class' => 'btn btn-light']) ?>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="order-details">
            <h3><i class="fas fa-truck"></i> Shipping Information</h3>
            <hr>
            <p>
                <strong>Address:</strong><br>
                <?= Html::encode($order->shipping_address) ?><br>
                <?= Html::encode($order->shipping_city) ?>, <?= Html::encode($order->shipping_postal_code) ?>
            </p>
        </div>
    </div>
    <div class="col-md-6">
        <div class="order-details">
            <h3><i class="fas fa-credit-card"></i> Billing Information</h3>
            <hr>
            <p>
                <strong>Name:</strong> <?= Html::encode($order->billing_first_name) ?> <?= Html::encode($order->billing_last_name) ?><br>
                <strong>Email:</strong> <?= Html::encode($order->billing_email) ?><br>
                <strong>Phone:</strong> <?= Html::encode($order->billing_phone) ?><br>
                <strong>Address:</strong> <?= Html::encode($order->billing_address) ?>, <?= Html::encode($order->billing_city) ?>, <?= Html::encode($order->billing_postal_code) ?>
            </p>
        </div>
    </div>
</div>

<div class="order-items">
    <h3><i class="fas fa-shopping-bag"></i> Order Items</h3>
    <hr>
    <table class="table-order-items">
        <thead>
            <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($order->orderItems as $item): ?>
                <tr>
                    <td><?= Html::encode($item->product->name ?? 'Product Deleted') ?></td>
                    <td>$<?= number_format($item->price, 2) ?></td>
                    <td><?= $item->quantity ?></td>
                    <td>$<?= number_format($item->price * $item->quantity, 2) ?></td>
                </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="3" class="text-end"><strong>Subtotal:</strong></td>
                <td>$<?= number_format($order->total_amount, 2) ?></td>
            </tr>
            <tr>
                <td colspan="3" class="text-end"><strong>Shipping:</strong></td>
                <td>$0.00</td>
            </tr>
            <tr>
                <td colspan="3" class="text-end"><strong>Total:</strong></td>
                <td>$<?= number_format($order->total_amount, 2) ?></td>
            </tr>
        </tbody>
    </table>
</div>

<div class="order-details">
    <h3><i class="fas fa-comment"></i> Order Notes</h3>
    <hr>
    <p><?= $order->order_notes ? Html::encode($order->order_notes) : 'No notes for this order.' ?></p>
</div>