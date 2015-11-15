<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Пользователи';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index table-responsive">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            'id',
            [
                'attribute'=>'item_name',
                'filter' => $searchModel->rolesList,
                'value' =>  function($data) {               // вывод списка ролей
                    $value = '';
                    foreach($data['role'] as $item)         // обращение к связи в модели User
                    {
                        $value .= $item['item_name'].' ';   // присваиваем значение
                    }
                    return $value;
                },
                /*'value' => function ($data) {
                    return $data->roleName;
                },*/
            ],
            'phone',
            'email:email',
            //'password_hash',
            [
                'attribute'=>'status',
                'filter' => $searchModel->statusList,
                'value' => function ($data) {
                    return $data->statusName;
                },
            ],
            // 'auth_key',
            [
                'attribute' => 'created_at',            // дата время выводится исходя из настроек форматерра в common/config/main.php
                'format' => ['date']
                //'format' => ['date', 'short']            // второй параметр объем информации о дате (short, medium, long, full)
            ],
            [
                'attribute' => 'updated_at',            // дата время выводится исходя из настроек форматерра в common/config/main.php
                'format' => ['date']
            ],
            // 'secret_key',
            [
                'class' => 'yii\grid\ActionColumn',
                'header'=>'Action',
                'headerOptions' => ['width' =>'65'],
                'template' => '{view} {update}{delete}',
            ],
        ],
    ]); ?>

</div>
