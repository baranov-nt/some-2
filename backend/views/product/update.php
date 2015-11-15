<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $modelProduct common\models\Product */

$this->title = 'Изменить продукт: ' . ' ' . $modelProduct->name;
$this->params['breadcrumbs'][] = ['label' => 'Продукты', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $modelProduct->name, 'url' => ['view', 'id' => $modelProduct->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="product-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'modelProduct' => $modelProduct,
    ]) ?>

</div>
