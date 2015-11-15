<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 16.10.2015
 * Time: 10:54
 */
/* @var $id int */
/* @var $idObject int */
/* @var $cropUrl string */
/* @var $attribute string */
/* @var $imagePath string */
/* @var $noImage string */

use yii\helpers\Html;
?>
<div class="col-xs-6" style="margin: 0 0 20px 0">
    <div id="image-container-<?= $id ?>" style="display:block; width: 100px; height: 100px;" class="img-previewFinal">
        <?= Html::img($noImage, ['style' => 'display:block; width: 100px; height: 100px;']) ?>
        <?php
        echo Html::input('hidden', 'phpntCrop[idObject]', $idObject);
        echo Html::input('hidden', 'phpntCrop[attribute]', $attribute);
        echo Html::input('hidden', 'phpntCrop[imagePath]', $imagePath);
        echo Html::input('hidden', 'phpntCrop[noImage]', $noImage);
        echo Html::input('hidden', 'phpntCrop[imageCrop]', null, ['id' => 'imageCrop-'.$id]);
        ?>
    </div>
    <div id="image-preview-container-<?= $id ?>" class="img-preview" style="display: none;">
        <?= Html::img('', ['style' => 'width: 100px; height: 100px;']) ?>
    </div>
</div>
<div class="col-xs-6">
    <?= $form->field($model, $attribute, [
        'template' => '<span id="crop-url-'.$model->user_id.'" class="btn btn-xs btn-primary btn-file pull-right" data-crop-url="'.$cropUrl.'" style="margin: 10px 0 0 0">
    Загрузить аватар{input}</span>'])->input('file', [
        'onchange' => 'loadFile(event)',
        'id' => 'control-'.$model->user_id,
    ])->label(false)->error(false); ?>


<!--    <span id="crop-url-<?/*= $id; */?>" class="btn btn-xs btn-primary btn-file pull-right" data-crop-url="<?/*= Url::to($cropUrl) */?>" style="margin: 10px 0 0 0">
        Загрузить аватар
        <?/*= Html::input('file', $attribute, null, [
            'onchange' => 'loadFile(event)',
            'id' => 'control-'.$id,
            ]); */?>
    </span>
--></div>