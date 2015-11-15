<?php

namespace common\widgets\SocialLinks;

use yii\web\AssetBundle;

class Assets extends AssetBundle{
	public $sourcePath = '@common/widgets/SocialLinks/assets';
    public $css = [
        'rrssb.css',
    ];
    public $js = [
        'rrssb.min.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}