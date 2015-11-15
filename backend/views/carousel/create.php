<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Carousel */

$this->title = 'Добавить элемент карусели';
$this->params['breadcrumbs'][] = ['label' => 'Карусель', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="carousel-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'modelCarousel' => $modelCarousel,
    ]) ?>

</div>
