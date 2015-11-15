<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $modelUser common\models\User */
/* @var $allRoles array */
/* @var $value array */

$this->title = 'Изменить пользователя: ' . ' ' . $modelUser->id;
$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $modelUser->id, 'url' => ['view', 'id' => $modelUser->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="user-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'modelUser' => $modelUser,
        'allRoles' => $allRoles,
        'value' => $value
    ]) ?>

</div>
