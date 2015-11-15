<?php
/**
 * Created by PhpStorm.
 * User: phpNT
 * Date: 24.09.2015
 * Time: 21:57
 */

namespace common\widgets\ImageLoad\assets;

use yii\web\AssetBundle;


class DistAsset extends AssetBundle
{
    public $sourcePath = '@common/widgets/ImageLoad';
    public $css = [
        'css/crop.css'
    ];
    public $js = [
        'js/crop.js'
    ];
}