<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
   // public $baseUrl = '@themes';
    public $css = [
        'css/site.css',
        'css/bootstrap.min.css',
        'css/index.css',
        'css/style.css',
        'css/bootstrap-tour.css',
        'css/sweetalert.css',
	'css/menu.css',
        'css/star-rating.css',
        'css/star-rating.min.css'
    ];
    public $js = [
        'js/function.js',
        'js/bootstrap.js',
        'js/bootstrap.min.js',
        'js/bootstrap-tour.js',
        'js/bootstrap-tour.min.js',
        'js/sweetalert.js',
        'js/jquery.blockUI.js',
        'js/notify.js',
        'js/jQuery.print.js',
        'js/star-rating.min.js',
        'js/star-rating.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
