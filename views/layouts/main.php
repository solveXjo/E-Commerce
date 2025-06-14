<?php

<<<<<<< HEAD
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
use yii\bootstrap5\Breadcrumbs;
use app\assets\AppAsset;
=======
/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
>>>>>>> afb9eb7f10e78b3a3c8b3f3442ae42b124538ca2

AppAsset::register($this);

$this->registerCsrfMetaTags();
<<<<<<< HEAD
$this->registerMetaTag(['charset' => 'UTF-8'], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/assets/img/favicon.png')]);

$isAdmin = !Yii::$app->user->isGuest && Yii::$app->user->identity->username === 'admin';

if ($isAdmin && Yii::$app->controller->id !== 'admin') {
    $currentRoute = Yii::$app->controller->id . '/' . Yii::$app->controller->action->id;
    $adminRoutes = ['product/index', 'admin/cart-item', 'admin/orders', 'admin/users', 'admin/reports'];

    if (!in_array($currentRoute, $adminRoutes) && $currentRoute !== '/site/logout') {
        Yii::$app->response->redirect(['/admin/dashboard']);
        return;
    }
}
=======
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);
>>>>>>> afb9eb7f10e78b3a3c8b3f3442ae42b124538ca2
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<<<<<<< HEAD

=======
>>>>>>> afb9eb7f10e78b3a3c8b3f3442ae42b124538ca2
<head>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<<<<<<< HEAD

<body class="d-flex flex-column h-100">
    <?php $this->beginBody() ?>

    <header>
        <?php
        if ($isAdmin) {
            NavBar::begin([
                'brandLabel' => '<i class="fas fa-shield-alt"></i> ' . Yii::$app->name . ' Admin',
                'brandUrl' => ['/admin/dashboard'],
                'options' => ['class' => 'navbar-expand-md navbar-dark bg-primary fixed-top']
            ]);
        } else {

            NavBar::begin([
                'brandLabel' => Yii::$app->name,
                'brandUrl' => Yii::$app->homeUrl,
                'options' => ['class' => 'navbar-expand-md navbar-dark bg-dark fixed-top']
            ]);
        }

        if ($isAdmin) {
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav ms-auto'],
                'items' => [
                    [
                        'label' => '<i class="fas fa-user-shield"></i> Admin Menu',
                        'encode' => false,
                        'options' => ['class' => 'nav-item dropdown'],
                        'items' => [
                            ['label' => '<i class="fas fa-tachometer-alt"></i> Dashboard', 'url' => ['/admin/dashboard'], 'encode' => false],
                            ['label' => '<i class="fas fa-box"></i> Products', 'url' => ['/admin/product/index'], 'encode' => false],

                            ['label' => '<i class="fas fa-receipt"></i> Orders', 'url' => ['/admin/orders'], 'encode' => false],
                            ['label' => '<i class="fas fa-shopping-cart"></i> Cart Items', 'url' => ['/cart-item'], 'encode' => false],
                            '<div class="dropdown-divider"></div>',
                            ['label' => '<i class="fas fa-eye"></i> View Site', 'url' => ['/site/index'], 'encode' => false],
                        ]
                    ],
                    [
                        'label' => '<i class="fas fa-sign-out-alt"></i> Logout (' . Yii::$app->user->identity->username . ')',
                        'encode' => false,
                        'url' => ['/site/logout'],
                        'linkOptions' => ['data-method' => 'post', 'class' => 'nav-link']
                    ]
                ]
            ]);
        } else {

            echo Nav::widget([
                'options' => ['class' => 'navbar-nav'],
                'items' => [
                    ['label' => 'Home', 'url' => ['/site/index']],
                    ['label' => 'About', 'url' => ['/site/about']],
                    ['label' => 'Contact', 'url' => ['/site/contact']],
                    ['label' => 'Shop', 'url' => ['site/shop']],

                    // Authentication links
                    Yii::$app->user->isGuest
                        ? ['label' => 'Signup', 'url' => ['/site/signup']]
                        : '',
                    Yii::$app->user->isGuest
                        ? ['label' => 'Login', 'url' => ['/site/login']]
                        : '<li class="nav-item">'
                        . Html::beginForm(['/site/logout'])
                        . Html::submitButton(
                            'Logout (' . Yii::$app->user->identity->username . ')',
                            ['class' => 'nav-link btn btn-link logout']
                        )
                        . Html::endForm()
                        . '</li>',

                    // Cart links for regular users only
                    ['label' => 'Cart', 'url' => ['/cart/index'], 'visible' => !Yii::$app->user->isGuest],
                    ['label' => 'My Orders', 'url' => ['/site/my-orders'], 'visible' => !Yii::$app->user->isGuest]
                ]
            ]);
        }

        NavBar::end();
        ?>
    </header>

    <main role="main" class="flex-shrink-0">
        <div class="">
            <?php if (isset($this->params['breadcrumbs'])): ?>
                <?= Breadcrumbs::widget([
                    'links' => $this->params['breadcrumbs'],
                ]) ?>
            <?php endif; ?>

            <?= $content ?>
        </div>
    </main>

    <footer class="footer mt-auto py-3 text-muted">
        <div class="">
            <p class="float-start">&copy; <?= Html::encode(Yii::$app->name) ?> <?= date('Y') ?></p>
            <p class="float-end"><?= Yii::powered() ?></p>
        </div>
    </footer>

    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>
=======
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<header id="header">
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => ['class' => 'navbar-expand-md navbar-dark bg-dark fixed-top']
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav'],
        'items' => [
            ['label' => 'Home', 'url' => ['/site/index']],
            ['label' => 'About', 'url' => ['/site/about']],
            ['label' => 'Contact', 'url' => ['/site/contact']],
            Yii::$app->user->isGuest
                ? ['label' => 'Login', 'url' => ['/site/login']]
                : '<li class="nav-item">'
                    . Html::beginForm(['/site/logout'])
                    . Html::submitButton(
                        'Logout (' . Yii::$app->user->identity->username . ')',
                        ['class' => 'nav-link btn btn-link logout']
                    )
                    . Html::endForm()
                    . '</li>'
        ]
    ]);
    NavBar::end();
    ?>
</header>

<main id="main" class="flex-shrink-0" role="main">
    <div class="container">
        <?php if (!empty($this->params['breadcrumbs'])): ?>
            <?= Breadcrumbs::widget(['links' => $this->params['breadcrumbs']]) ?>
        <?php endif ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</main>

<footer id="footer" class="mt-auto py-3 bg-light">
    <div class="container">
        <div class="row text-muted">
            <div class="col-md-6 text-center text-md-start">&copy; My Company <?= date('Y') ?></div>
            <div class="col-md-6 text-center text-md-end"><?= Yii::powered() ?></div>
        </div>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
>>>>>>> afb9eb7f10e78b3a3c8b3f3442ae42b124538ca2
