<?php
use frontend\assets\AppAsset;
use yii\bootstrap\NavBar;
use yii\bootstrap\Nav;
use yii\helpers\Html;
use common\widgets\Alert;
use yii\widgets\Breadcrumbs;
/**
 * Created by PhpStorm.
 * User: phpNT
 * Date: 28.02.2015
 * Time: 1:48
 */
/* @var $content string
 * @var $this \yii\web\View */
AppAsset::register($this);
$this->beginPage();
?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <?= Html::csrfMetaTags() ?>
        <meta charset="<?= Yii::$app->charset ?>">
        <?php $this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1']); ?>
        <title><?= Yii::$app->name ?></title>
        <?php $this->head() ?>
    </head>
    <body>
    <?php $this->beginBody(); ?>

    <div class="wrap">
        <?php
        NavBar::begin(
            [
                'options' => [
                    'class' => 'navbar navbar-default',
                    'style' => 'padding: 0; margin: 0',
                    'id' => 'main-menu'
                ],
                'renderInnerContainer' => true,
                'innerContainerOptions' => [
                    'class' => 'container'
                ],
                'brandLabel' => 'Управление сайтом',
                'brandUrl' => [
                    '/site/index'
                ],
                'brandOptions' => [
                    'class' => 'navbar-brand'
                ]
            ]
        );

        if(Yii::$app->user->can('Редактор')):
            $menuItems = [
                [
                    'label' => 'Управление контентом <span class="glyphicon glyphicon-th-list"></span>',
                    'items' => [
                        '<li class="dropdown-header">Выбрать раздел</li>',
                        '<li class="divider"></li>',
                        [
                            'label' => 'Карусель',
                            'url' => ['/carousel/index']
                        ],
                        [
                            'label' => 'Продукты',
                            'url' => ['/product/index']
                        ],
                    ]
                ],
            ];
        endif;

        if(Yii::$app->user->can('Администратор')):
            $menuItems[] =
                [
                    'label' => 'Управление приложением <span class="glyphicon glyphicon-cog"></span>',
                    'items' => [
                        '<li class="dropdown-header">Выбрать раздел </li>',
                        '<li class="divider"></li>',
                            [
                                'label' => 'Управление пользователями',
                                'url' => ['/user/index']
                            ],
                        [
                            'label' => 'Категории',
                            'url' => ['/category/index']
                        ],
                    ]
            ];
        endif;


        if (Yii::$app->user->isGuest) {
            $menuItems[] = ['label' => 'Войти', 'url' => ['/site/login']];
        } else {
            $menuItems[] = [
                'label' => 'Выйти (' . Yii::$app->user->identity->email . ')',
                'url' => ['/site/logout'],
                'linkOptions' => ['data-method' => 'post']
            ];
        }

        echo Nav::widget([
            'items' => $menuItems,
            'activateParents' => true,
            'encodeLabels' => false,
            'options' => [
                'class' => 'navbar-nav navbar-right'
            ]
        ]);

        NavBar::end();
           ?>

            <div class="container">
                <?= Breadcrumbs::widget([
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                ]) ?>
                <?= Alert::widget() ?>
                <?= $content ?>
            </div>
    </div>

    <footer class="footer">
        <div class="container" >
            <span class="badge badge-primary">
                <span class="glyphicon glyphicon-copyright-mark"></span> Бояр <?= date('Y') ?>
            </span>
        </div>
    </footer>

    <?php $this->endBody(); ?>
    </body>
    </html>
<?php
$this->endPage();