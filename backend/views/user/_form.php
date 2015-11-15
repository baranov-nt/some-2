<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $modelUser common\models\User */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $allRoles array */
/* @var $value array */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($modelUser, 'phone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($modelUser, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($modelUser, 'status')->dropDownList($modelUser->statusList) ?>

    <?php /*echo $form->field($modelUser, 'secret_key')->textInput(['maxlength' => true])*/ ?>

    <?php
    $modelUser->item_name = $value;

    echo $form->field($modelUser, 'item_name')->inline(false)->checkboxlist($allRoles);
    ?>

    <div class="form-group">
        <?= Html::submitButton($modelUser->isNewRecord ? 'Create' : 'Update', ['class' => $modelUser->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
