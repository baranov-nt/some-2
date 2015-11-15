<?php

namespace common\widgets\ImageLoad\assets;

use yii\web\AssetBundle;

/**
 * CropperAsset
 *
 * Установка - composer require "bower-asset/cropper"
 *
 */
class CropperAsset extends AssetBundle
{
    public $sourcePath = '@bower';
    public $css = [
        'cropper/dist/cropper.css',
    ];
    public $js = [
        'cropper/dist/cropper.js',
    ];
    /*public $jsOptions = [
        'position' => \yii\web\View::POS_HEAD
    ];*/
    public $depends = [
        'yii\web\JqueryAsset',
        'common\widgets\ImageLoad\assets\DistAsset',
    ];
}
