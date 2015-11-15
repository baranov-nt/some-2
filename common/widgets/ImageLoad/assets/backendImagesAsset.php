<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 26.10.2015
 * Time: 15:33
 * Подключение файлов изображений из backend
 */

namespace common\widgets\ImageLoad\assets;

use yii\web\AssetBundle;


class BackendImagesAsset extends AssetBundle
{
    public $sourcePath = '@backend/web/images'; // копируется все содержимое, например папки css, js, fonts, images

    public function init()
    {
        parent::init();
    }

    /*public $publishOptions = [
        'forceCopy'=>true
    ];*/
}
