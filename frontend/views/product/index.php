<?php
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\widgets\Pjax;
use yii\widgets\ListView;
use yii\helpers\Html;
use yii\helpers\Url;

Pjax::begin([
    'id' => 'show-products',
    'enablePushState' => false
]);
?>

<p>
    <?= Html::button('Все', [
        'class' => (Yii::$app->controller->action->id == 'index') ? 'btn btn-success' : 'btn btn-default',
        'onClick'=>'
            $.pjax({
            type: "POST",
            url: "'.Url::to(['/product/index']).'",
            container: "#show-products",
        });
    ']);
    ?>
    <?= Html::button('Мясо', [
        'class' => (Yii::$app->controller->action->id == 'meat') ? 'btn btn-success' : 'btn btn-default',
        'onClick'=>'
            $.pjax({
            type: "POST",
            url: "'.Url::to(['/product/meat']).'",
            container: "#show-products",
        });
    ']);
    ?>
    <?= Html::button('Рыба', [
        'class' => (Yii::$app->controller->action->id == 'fish') ? 'btn btn-success' : 'btn btn-default',
        'onClick'=>'
            $.pjax({
            type: "POST",
            url: "'.Url::to(['/product/fish']).'",
            container: "#show-products",
        });
    ']);
    ?>
</p>

<?= ListView::widget([
    'dataProvider' => $dataProvider,
    'layout' => "{summary}\n{items}<div class='col-md-12'>{pager}</div>",              // выводит следующии данные summary(вывод количества записей), items(вывод самих записей),
    // sorter(вывод блока сортировки), pager(вывод пагинации)
    //'itemView' => 'index',                                                // представление для элементов
    'itemView' => function ($model, $key, $index, $widget) {                // альтернативный способ передать данные в представление
        return $this->render('_products',[
            'model' => $model,
            'key' => $key,
            'index' => $index,
            'widget' => $widget
        ]);
        // or just do some echo
        //return $model->name . ' добавил ' . $model->user->email;
    },
    'itemOptions' => [                                                      // свойства для элементов контейнера
        'tag' => 'div',
        'class' => 'col-md-4',
        //'id' => 'list-wrapper',
    ],
    'pager' => [                                                            // параметры для пагинации
        'firstPageLabel' => 'первая',
        'lastPageLabel' => 'последняя',
        'nextPageLabel' => 'следующая',
        'prevPageLabel' => 'предыдущая',
        'maxButtonCount' => 3,                                              // количество цифровых кнопок
    ],
    //'summary' => "{begin}{end}{count}{totalCount}{page}{pageCount}",      // свойства выводимых данных количества элементов
    'summaryOptions' => [                                                   // свойства для количества элементов
        'tag' => 'div',
        'class' => 'col-md-12',
        //'id' => 'list-wrapper',
    ],
    'options' => [                                                          // свойства основного контейнера для элементов
        'tag' => 'div',
        'class' => 'list-wrapper row',
        'id' => 'list-wrapper',
    ],

]);
Pjax::end();



