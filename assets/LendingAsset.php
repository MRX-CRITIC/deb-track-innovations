<?php

namespace app\assets;

class LendingAsset extends \yii\web\AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/font-awesome.min.css',
        'css/LineIcons.css',
        'css/style.css',
    ];

    public $js = [
    ];
}