<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class Select23Asset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'http://cdnjs.cloudflare.com/ajax/libs/select2/3.5.2/select2.min.css',
        'css/select2-bootstrap.css',
    ];
    public $js = [
        'http://cdnjs.cloudflare.com/ajax/libs/lodash.js/3.7.0/lodash.min.js',
        'http://cdnjs.cloudflare.com/ajax/libs/select2/3.5.2/select2.min.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}