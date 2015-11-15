<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $modelProduct common\models\Product */

$this->title = $modelProduct->name;
$this->params['breadcrumbs'][] = ['label' => 'Продукты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $modelProduct->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $modelProduct->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $modelProduct,
        'attributes' => [
            'id',
            'images_num',
            'images_label',
            'name',
            'desc:ntext',
            'price',
            'rate',
            'created_at',
            'updated_at',
            'category.name',
            'unit_id',
            'user_id',
        ],
    ]) ?>

</div>
