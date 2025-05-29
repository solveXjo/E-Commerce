<?php
<<<<<<< HEAD

=======
>>>>>>> afb9eb7f10e78b3a3c8b3f3442ae42b124538ca2
/**
 * @link https://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license https://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

<<<<<<< HEAD
=======
/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
>>>>>>> afb9eb7f10e78b3a3c8b3f3442ae42b124538ca2
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
<<<<<<< HEAD
        'assets/css/all.min.css',
        'assets/bootstrap/css/bootstrap.min.css',
        'assets/css/owl.carousel.css',
        'assets/css/magnific-popup.css',
        'assets/css/animate.css',
        'assets/css/meanmenu.min.css',
        'assets/css/main.css',
        'assets/css/responsive.css',
    ];
    public $js = [
        'assets/js/jquery-1.11.3.min.js',
        'assets/bootstrap/js/bootstrap.min.js',
        'assets/js/jquery.countdown.js',
        'assets/js/jquery.isotope-3.0.6.min.js',
        'assets/js/waypoints.js',
        'assets/js/owl.carousel.min.js',
        'assets/js/jquery.magnific-popup.min.js',
        'assets/js/jquery.meanmenu.min.js',
        'assets/js/sticker.js',
        'assets/js/main.js',
=======
        'css/site.css',
    ];
    public $js = [
>>>>>>> afb9eb7f10e78b3a3c8b3f3442ae42b124538ca2
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap5\BootstrapAsset'
    ];
}
