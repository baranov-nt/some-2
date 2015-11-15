<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;
use yii\authclient\widgets\AuthChoice;

/* @var $this yii\web\View */
/* @var $model common\models\LoginForm  */
/* @var $form ActiveForm */
?>
<div class="main-login">
    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
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
            <?= Html::a('Забыли пароль?', ['/main/send-email']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
    <div class="row" style="margin-top: 20px;">
        <div class="col-md-4 col-md-offset-4">
            <label class="control-label" for="loginform-email">Войти через социальную сеть</label>
            <?php $authAuthChoice = AuthChoice::begin([
                'baseAuthUrl' => ['site/auth'],
            ]); ?>
            <?php foreach ($authAuthChoice->getClients() as $client): ?>
                <div style="width: 40px; float: left; font-size: 0px;"><?php $authAuthChoice->clientLink($client) ?></div>
            <?php endforeach; ?>

            <?php AuthChoice::end(); ?>
        </div>
    </div>
</div><!-- main-login -->
