<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 05.11.2015
 * Time: 8:35
 */

/* @var $modelProfile \common\models\Profile */
?>
<div class="row">
    <div class="col-md-3">
        <?php
        foreach($modelProfile->imagesOfObjects as $one):
            ?>
            <img src="<?= Yii::$app->urlManager->createAbsoluteUrl('').'images/'.$one->image->path_small_image ?>" class="col-md-12"/>
            <?php
        endforeach;
        ?>
    </div>
    <div class="col-md-9">
        <div class="col-md-12"><?= $modelProfile->first_name ?></div>
        <div class="col-md-12"><?= $modelProfile->second_name ?></div>
        <div class="col-md-12"><?= $modelProfile->middle_name ?></div>
    </div>
</div>

