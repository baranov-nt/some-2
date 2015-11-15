<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $modelCarousel common\models\Carousel */

$this->title = 'Изменить элемент карусели: ' . ' ' . $modelCarousel->id;
$this->params['breadcrumbs'][] = ['label' => 'Карусель', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $modelCarousel->id, 'url' => ['view', 'id' => $modelCarousel->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="carousel-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'modelCarousel' => $modelCarousel,
    ]) ?>

</div>
