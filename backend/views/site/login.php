<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $model common\models\LoginForm */
/* @var $form ActiveForm */
?>
<div class="main-login">

    <?php $form = ActiveForm::begin(); ?>

    <?php if($model->scenario === 'loginWithEmail'): ?>
        <?= $form->field($model, 'email') ?>
    <?php else: ?>
        <?= $form->field($model, 'phone')->widget(MaskedInput::className(),[
            'name' => 'phone',
            'mask' => '7 (999) 999-9999',
            'options' => [
                'placeholder' => '7 (___) ___-____',
                'class' => 'form-control'
            ]]) ?>
    <?php endif; ?>
    <?= $form->field($model, 'password')->passwordInput() ?>
    <?= $form->field($model, 'rememberMe')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton('Войти', ['class' => 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>

    <?= Html::a('Забыли пароль?', ['/main/send-email']) ?>

</div><!-- main-login -->
