<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Order Details - ' . $order->order_number;
$this->params['breadcrumbs'][] = ['label' => 'Dashboard', 'url' => ['/admin/dashboard']];
$this->params['breadcrumbs'][] = ['label' => 'Orders', 'url' => ['/admin/orders']];
$this->params['breadcrumbs'][] = $order->order_number;

$this->registerCss('
    .order-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 25px;
        border-radius: 15px;
        margin-bottom: 30px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }
    .info-card {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        margin-bottom: 20px;
        border-left: 5px solid #007bff;
    }
    .status-update-card {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        border-left: 5px solid #28a745;
    }
    .order-items-table {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        margin-bottom: 20px;
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
    .total-section {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
        padding: 20px;
        border-radius: 10px;
        text-align: center;
    }
    .status-badge {
        font-size: 1em;
        padding: 8px 15px;
        border-radius: 25px;
    }
    .timeline {
        position: relative;
        padding-left: 30px;
    }
    .timeline::before {
        content: "";
        position: absolute;
        left: 15px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #dee2e6;
    }
    .timeline-item {
        position: relative;
        margin-bottom: 20px;
    }
    .timeline-item::before {
        content: "";
        position: absolute;
        left: -22px;
        top: 8px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #007bff;
        border: 2px solid white;
        box-shadow: 0 0 5px rgba(0,0,0,0.2);
    }
');
?>

<div class="order-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1><i class="fas fa-receipt"></i> Order <?= Html::encode($order->order_number) ?></h1>
            <p class="mb-0">Placed on <?= date('F j, Y \a\t g:i A', strtotime($order->created_at)) ?></p>
        </div>
        <div class="text-end">
            <div class="mb-2">
                <?= Html::tag('span', ucfirst($order->status), [
                    'class' => 'badge bg-' . $order->statusBadgeClass . ' status-badge'
                ]) ?>
            </div>
            <div>
                <?= Html::tag('span', ucfirst($order->payment_status), [
                    'class' => 'badge bg-' . $order->paymentStatusBadgeClass . ' status-badge'
                ]) ?>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Order Information -->
    <div class="col-lg-8">
        <!-- Customer Information -->
        <div class="info-card">
            <h4><i class="fas fa-user text-primary"></i> Customer Information</h4>
            <div class="row mt-3">
                <div class="col-md-6">
                    <strong>Customer:</strong><br>
                    <?= Html::encode($order->user ? $order->user->username : 'Unknown User') ?>
                </div>
                <div class="col-md-6">
                    <strong>Email:</strong><br>
                    <?= Html::encode($order->user ? $order->user->email : 'N/A') ?>
                </div>
            </div>
        </div>

        <!-- Shipping Information -->
        <div class="info-card">
            <h4><i class="fas fa-truck text-primary"></i> Shipping Information</h4>
            <div class="row mt-3">
                <div class="col-md-6">
                    <strong>Shipping Address:</strong><br>
                    <?= nl2br(Html::encode($order->shipping_address)) ?>
                </div>
                <div class="col-md-6">
                    <strong>Billing Address:</strong><br>
                    <?= nl2br(Html::encode($order->billing_address ?: $order->shipping_address)) ?>
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <div class="order-items-table">
            <div class="p-3 border-bottom">
                <h4><i class="fas fa-shopping-cart text-primary"></i> Order Items</h4>
            </div>
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th class="text-center">Quantity</th>
                        <th class="text-end">Price</th>
                        <th class="text-end">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $subtotal = 0; ?>
                    <?php foreach ($order->orderItems as $item): ?>
                        <?php $subtotal += $item->total; ?>
                        <tr>
                            <td>
                                <strong><?= Html::encode($item->product_name) ?></strong>
                                <?php if ($item->product): ?>
                                    <br><small class="text-muted">SKU: <?= Html::encode($item->product->ProductID) ?></small>
                                <?php endif; ?>
                            </td>
                            <td class="text-center"><?= $item->quantity ?></td>
                            <td class="text-end">$<?= number_format($item->price, 2) ?></td>
                            <td class="text-end"><strong>$<?= number_format($item->total, 2) ?></strong></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr class="table-active">
                        <td colspan="3" class="text-end"><strong>Subtotal:</strong></td>
                        <td class="text-end"><strong>$<?= number_format($subtotal, 2) ?></strong></td>
                    </tr>
                    <tr class="table-active">
                        <td colspan="3" class="text-end"><strong>Shipping:</strong></td>
                        <td class="text-end"><strong>$0.00</strong></td>
                    </tr>
                    <tr class="table-success">
                        <td colspan="3" class="text-end"><strong>Total:</strong></td>
                        <td class="text-end"><strong>$<?= number_format($order->total_amount, 2) ?></strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Order Summary -->
        <div class="info-card">
            <h4><i class="fas fa-info-circle text-success"></i> Order Summary</h4>
            <div class="mt-3">
                <div class="d-flex justify-content-between mb-2">
                    <span>Order Number:</span>
                    <strong><?= Html::encode($order->order_number) ?></strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Order Date:</span>
                    <span><?= date('M j, Y', strtotime($order->created_at)) ?></span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Payment Method:</span>
                    <span><?= Html::encode($order->payment_method ?: 'N/A') ?></span>
                </div>
                <hr>
                <div class="total-section">
                    <h3 class="mb-0">$<?= number_format($order->total_amount, 2) ?></h3>
                    <small>Total Amount</small>
                </div>
            </div>
        </div>

        <!-- Update Status -->
        <?php if ($order->status !== 'delivered' && $order->status !== 'cancelled'): ?>
            <div class="status-update-card">
                <h4><i class="fas fa-edit text-success"></i> Update Status</h4>
                <?php $form = ActiveForm::begin([
                    'action' => ['/admin/update-order-status', 'id' => $order->id],
                    'options' => ['class' => 'mt-3']
                ]); ?>

                <div class="mb-3">
                    <?= Html::dropDownList('status', $order->status, $order::getStatusOptions(), [
                        'class' => 'form-select',
                        'id' => 'status-select'
                    ]) ?>
                </div>

                <div class="d-grid">
                    <?= Html::submitButton('<i class="fas fa-save"></i> Update Status', [
                        'class' => 'btn btn-success'
                    ]) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        <?php endif; ?>

        <!-- Order Timeline -->
        <div class="info-card">
            <h4><i class="fas fa-history text-info"></i> Order Timeline</h4>
            <div class="timeline mt-3">
                <div class="timeline-item">
                    <strong>Order Placed</strong><br>
                    <small class="text-muted"><?= date('M j, Y g:i A', strtotime($order->created_at)) ?></small>
                </div>

                <?php if ($order->status !== 'pending'): ?>
                    <div class="timeline-item">
                        <strong>Order Confirmed</strong><br>
                        <small class="text-muted">Status updated to <?= ucfirst($order->status) ?> on <?= date('M j, Y g:i A', strtotime($order->updated_at)) ?></small>
                    </div>
                <?php endif; ?>
                <?php if ($order->status === 'shipped' || $order->status === 'delivered'): ?>
                    <div class="timeline-item">
                        <strong>Order Shipped</strong><br>
                        <small class="text-muted
">Shipped on <?= date('M j, Y g:i A', strtotime($order->updated_at)) ?></small>
                    </div>
                <?php endif; ?>
                <?php if ($order->status === 'delivered'): ?>
                    <div class="timeline-item">
                        <strong>Order Delivered</strong><br>
                        <small class="text-muted
">Delivered on <?= date('M j, Y g:i A', strtotime($order->updated_at)) ?></small>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>