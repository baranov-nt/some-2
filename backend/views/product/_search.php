<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $modelProduct common\models\ProductSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="product-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($modelProduct, 'id') ?>

    <?= $form->field($modelProduct, 'images_num') ?>

    <?= $form->field($modelProduct, 'images_label') ?>

    <?= $form->field($modelProduct, 'name') ?>

    <?= $form->field($modelProduct, 'desc') ?>

    <?php // echo $form->field($modelProduct, 'price') ?>

    <?php // echo $form->field($modelProduct, 'rate') ?>

    <?php // echo $form->field($modelProduct, 'created_at') ?>

    <?php // echo $form->field($modelProduct, 'updated_at') ?>

    <?php // echo $form->field($modelProduct, 'category_id') ?>

    <?php // echo $form->field($modelProduct, 'unit_id') ?>

    <?php // echo $form->field($modelProduct, 'user_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
