<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
use yii\bootstrap5\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => 'UTF-8'], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/assets/img/favicon.png')]);

$this->registerCss('
    body { 
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
    }
    .admin-navbar {
        background: linear-gradient(90deg, #2c3e50 0%, #34495e 100%) !important;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        border-bottom: 3px solid #3498db;
    }
    .admin-sidebar {
        background: linear-gradient(180deg, #2c3e50 0%, #34495e 100%);
        min-height: calc(100vh - 76px);
        box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        border-right: 3px solid #3498db;
    }
    .admin-content {
        background: rgba(255,255,255,0.95);
        min-height: calc(100vh - 76px);
        border-radius: 15px 0 0 0;
        box-shadow: -2px 0 20px rgba(0,0,0,0.1);
    }
    .sidebar-nav {
        padding: 20px 0;
    }
    .sidebar-nav .nav-link {
        color: #ecf0f1 !important;
        padding: 15px 25px;
        margin: 5px 15px;
        border-radius: 8px;
        transition: all 0.3s ease;
        border-left: 4px solid transparent;
    }
    .sidebar-nav .nav-link:hover {
        background: rgba(52, 152, 219, 0.2);
        border-left-color: #3498db;
        transform: translateX(5px);
    }
    .sidebar-nav .nav-link.active {
        background: linear-gradient(90deg, #3498db, #2980b9);
        border-left-color: #fff;
    }
    .admin-brand {
        color: #3498db !important;
        font-weight: bold;
        font-size: 1.4em;
    }
    .admin-welcome {
        background: linear-gradient(135deg, #3498db, #2980b9);
        color: white;
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 30px;
        box-shadow: 0 5px 15px rgba(52, 152, 219, 0.3);
    }
    .stats-card {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        border-left: 5px solid #3498db;
        transition: transform 0.3s ease;
    }
    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    }
    .breadcrumb {
        background: transparent;
        padding: 20px 0 10px 0;
    }
    .breadcrumb-item + .breadcrumb-item::before {
        color: #7f8c8d;
    }
');
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">

<head>
    <title><?= Html::encode($this->title) ?> - Admin Panel</title>
    <?php $this->head() ?>
</head>

<body class="d-flex flex-column h-100">
    <?php $this->beginBody() ?>

    <header>
        <?php
        NavBar::begin([
            'brandLabel' =>  Yii::$app->name . ' Admin',
            'brandUrl' => ['/admin/dashboard'],
            'options' => [
                'class' => 'navbar-expand-md navbar-dark admin-navbar fixed-top',
                'style' => 'padding: 0.8rem 1rem;'
            ],
            'brandOptions' => ['class' => 'admin-brand']
        ]);

        echo Nav::widget([
            'options' => ['class' => 'navbar-nav ms-auto'],
            'items' => [
                [
                    'label' => '<i class="fas fa-user-circle"></i> ' . Yii::$app->user->identity->username,
                    'encode' => false,
                    'options' => ['class' => 'nav-item dropdown'],
                    'items' => [
                        // ['label' => '<i class="fas fa-cog"></i> Settings', 'url' => ['/admin/settings'], 'encode' => false],
                        // ['label' => '<i class="fas fa-eye"></i> View Site', 'url' => ['/site/index'], 'encode' => false],
                        // '<div class="dropdown-divider"></div>',
                        [
                            'label' => '<i class="fas fa-sign-out-alt"></i> Logout',
                            'encode' => false,
                            'url' => ['/site/logout'],
                            'linkOptions' => ['data-method' => 'post']
                        ]
                    ]
                ]
            ]
        ]);

        NavBar::end();
        ?>
    </header>

    <main role="main" class="flex-shrink-0" style="margin-top: 76px;">
        <div class="p-0">
            <div class="row g-0">
                <!-- Admin Sidebar -->
                <div class="col-md-3 col-lg-2 admin-sidebar">
                    <nav class="sidebar-nav">
                        <?= Nav::widget([
                            'options' => ['class' => 'nav flex-column'],
                            'items' => [
                                [
                                    'label' => '<i class="fas fa-tachometer-alt"></i> Dashboard',
                                    'url' => ['/admin/dashboard'],
                                    'encode' => false,
                                    'active' => Yii::$app->controller->id == 'admin' && Yii::$app->controller->action->id == 'dashboard'
                                ],
                                [
                                    'label' => '<i class="fas fa-box"></i> Products',
                                    'url' => ['/admin/products'],
                                    'encode' => false,
                                    'active' => Yii::$app->controller->id == 'admin' && in_array(Yii::$app->controller->action->id, ['products', 'product-create', 'product-view', 'product-update'])
                                ],
                                [
                                    'label' => '<i class="fas fa-shopping-cart"></i> Cart Items',
                                    'url' => ['/admin/cart-items'],
                                    'encode' => false,
                                    'active' => Yii::$app->controller->id == 'admin' && Yii::$app->controller->action->id == 'cart-items'
                                ],
                                [
                                    'label' => '<i class="fas fa-receipt"></i> Orders',
                                    'url' => ['/admin/orders'],
                                    'encode' => false,
                                    'active' => Yii::$app->controller->id == 'admin' && Yii::$app->controller->action->id == 'orders'
                                ],
                                [
                                    'label' => '<i class="fas fa-users"></i> Users',
                                    'url' => ['/admin/users'],
                                    'encode' => false,
                                    'active' => Yii::$app->controller->id == 'admin' && Yii::$app->controller->action->id == 'users'
                                ],
                                [
                                    'label' => '<i class="fas fa-chart-bar"></i> Reports',
                                    'url' => ['/admin/reports'],
                                    'encode' => false,
                                    'active' => Yii::$app->controller->id == 'admin' && Yii::$app->controller->action->id == 'reports'
                                ]
                            ]
                        ]) ?>
                    </nav>
                </div>

                <div class="col-md-9 col-lg-10 admin-content">
                    <div class="py-4">
                        <?php if (isset($this->params['breadcrumbs'])): ?>
                            <?= Breadcrumbs::widget([
                                'links' => $this->params['breadcrumbs'],
                                'options' => ['class' => 'breadcrumb']
                            ]) ?>
                        <?php endif; ?>

                        <?= $content ?>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="footer mt-auto py-3 text-center" style="background: rgba(44, 62, 80, 0.9); color: white;">
        <div class="">
            <p class="mb-0">&copy; <?= date('Y') ?> <?= Yii::$app->name ?> Admin Panel. All rights reserved.</p>
        </div>
    </footer>

    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>