<?php

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Dashboard';
$this->params['breadcrumbs'][] = $this->title;

// Register Chart.js from CDN
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js', ['position' => \yii\web\View::POS_HEAD]);

// Custom CSS for dashboard
$this->registerCss('
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }
    .stat-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 25px;
        border-radius: 15px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        transition: transform 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.15);
    }
    .stat-card::before {
        content: "";
        position: absolute;
        top: 0;
        right: 0;
        width: 100px;
        height: 100px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
        transform: translate(30px, -30px);
    }
    .stat-card.products { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
    .stat-card.users { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
    .stat-card.orders { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
    .stat-card.revenue { background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); }
    
    .stat-number {
        font-size: 2.5rem;
        font-weight: bold;
        margin-bottom: 5px;
    }
    .stat-label {
        font-size: 1.1rem;
        opacity: 0.9;
        margin-bottom: 10px;
    }
    .recent-activity {
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        padding: 25px;
        margin-bottom: 30px;
    }
    .activity-header {
        border-bottom: 2px solid #f8f9fa;
        padding-bottom: 15px;
        margin-bottom: 20px;
    }
    .activity-item {
        padding: 15px 0;
        border-bottom: 1px solid #f1f3f4;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .activity-item:last-child {
        border-bottom: none;
    }
    .low-stock-alert {
        background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);
        border: none;
        color: #721c24;
    }
    .chart-container {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        margin-bottom: 30px;
    }
');

// Chart data for monthly sales
$chartLabels = json_encode(array_column($monthlySales, 'month'));
$chartData = json_encode(array_column($monthlySales, 'sales'));

$this->registerJs("
    // Monthly Sales Chart
    const ctx = document.getElementById('salesChart').getContext('2d');
    const salesChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: $chartLabels,
            datasets: [{
                label: 'Monthly Sales',
                data: $chartData,
                borderColor: 'rgb(102, 126, 234)',
                backgroundColor: 'rgba(102, 126, 234, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return ' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });
");
?>

<div class="admin-welcome">
    <h1><i class="fas fa-tachometer-alt"></i> Admin Dashboard</h1>
    <p class="mb-0">Welcome back, <?= Html::encode(Yii::$app->user->identity->username) ?>! Here's what's happening with your store today.</p>
</div>

<!-- Statistics Cards -->
<div class="stats-grid">
    <div class="stat-card products">
        <div class="stat-number"><?= number_format($totalProducts) ?></div>
        <div class="stat-label">Total Products</div>
        <small><i class="fas fa-box"></i> Products in inventory</small>
    </div>

    <div class="stat-card users">
        <div class="stat-number"><?= number_format($totalUsers) ?></div>
        <div class="stat-label">Registered Users</div>
        <small><i class="fas fa-users"></i> Active customers</small>
    </div>

    <div class="stat-card orders">
        <div class="stat-number"><?= number_format($totalOrders) ?></div>
        <div class="stat-label">Total Orders</div>
        <small><i class="fas fa-shopping-cart"></i> Orders processed</small>
    </div>

    <div class="stat-card revenue">
        <div class="stat-number">$<?= number_format($totalRevenue, 2) ?></div>
        <div class="stat-label">Total Revenue</div>
        <small><i class="fas fa-dollar-sign"></i> Lifetime earnings</small>
    </div>
</div>

<div class="row">
    <!-- Monthly Sales Chart -->
    <div class="col-lg-8">
        <div class="chart-container">
            <div class="activity-header">
                <h4><i class="fas fa-chart-line text-primary"></i> Monthly Sales Trend</h4>
                <small class="text-muted">Sales performance over the last 12 months</small>
            </div>
            <canvas id="salesChart" height="100"></canvas>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="col-lg-4">
        <div class="recent-activity">
            <div class="activity-header">
                <h4><i class="fas fa-bolt text-warning"></i> Quick Actions</h4>
            </div>
            <div class="d-grid gap-2">
                <?= Html::a('<i class="fas fa-plus"></i> Add New Product', ['/admin/product-create'], ['class' => 'btn btn-primary']) ?>
                <?= Html::a('<i class="fas fa-eye"></i> View All Orders', ['/admin/orders'], ['class' => 'btn btn-info']) ?>
                <?= Html::a('<i class="fas fa-users"></i> Manage Users', ['/admin/users'], ['class' => 'btn btn-success']) ?>
                <?= Html::a('<i class="fas fa-chart-bar"></i> View Reports', ['/admin/reports'], ['class' => 'btn btn-warning']) ?>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Products -->
    <div class="col-md-4">
        <div class="recent-activity">
            <div class="activity-header">
                <h4><i class="fas fa-clock text-success"></i> Recent Products</h4>
            </div>
            <?php if (!empty($recentProducts)): ?>
                <?php foreach ($recentProducts as $product): ?>
                    <div class="activity-item">
                        <div>
                            <strong><?= Html::encode($product->Name) ?></strong><br>
                            <small class="text-muted">$<?= number_format($product->Price, 2) ?> | Stock: <?= $product->StockQuantity ?></small>
                        </div>
                        <small class="text-muted"><?= Yii::$app->formatter->asRelativeTime($product->CreatedAt) ?></small>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-muted">No recent products found.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="col-md-4">
        <div class="recent-activity">
            <div class="activity-header">
                <h4><i class="fas fa-receipt text-info"></i> Recent Orders</h4>
            </div>
            <?php if (!empty($recentOrders)): ?>
                <?php foreach ($recentOrders as $order): ?>
                    <div class="activity-item">
                        <div>
                            <strong><?= Html::encode($order->order_number) ?></strong><br>
                            <small class="text-muted">
                                <?= Html::encode($order->user->username ?? 'Unknown') ?> |
                                <span class="badge bg-<?= $order->statusBadgeClass ?>"><?= ucfirst($order->status) ?></span>
                            </small>
                        </div>
                        <div class="text-end">
                            <strong>$<?= number_format($order->total_amount, 2) ?></strong><br>
                            <small class="text-muted"><?= Yii::$app->formatter->asRelativeTime($order->created_at) ?></small>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-muted">No recent orders found.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Low Stock Alert -->
    <div class="col-md-4">
        <div class="recent-activity low-stock-alert">
            <div class="activity-header" style="border-color: rgba(114, 28, 36, 0.2);">
                <h4><i class="fas fa-exclamation-triangle"></i> Low Stock Alert</h4>
            </div>
            <?php if (!empty($lowStockProducts)): ?>
                <?php foreach ($lowStockProducts as $product): ?>
                    <div class="activity-item" style="border-color: rgba(114, 28, 36, 0.2);">
                        <div>
                            <strong><?= Html::encode($product->Name) ?></strong><br>
                            <small>Only <?= $product->StockQuantity ?> left in stock</small>
                        </div>
                        <span class="badge bg-danger"><?= $product->StockQuantity ?></span>
                    </div>
                <?php endforeach; ?>
                <div class="mt-3">
                    <?= Html::a('<i class="fas fa-plus"></i> Restock Products', ['/product/index'], ['class' => 'btn btn-outline-danger btn-sm']) ?>
                </div>
            <?php else: ?>
                <p class="text-success"><i class="fas fa-check-circle"></i> All products are well stocked!</p>
            <?php endif; ?>
        </div>
    </div>
</div>