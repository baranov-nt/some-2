<?php
use frontend\assets\AppAsset;
use yii\bootstrap\NavBar;
use yii\bootstrap\Nav;
use yii\bootstrap\Modal;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use common\widgets\Alert;
use yii\helpers\Url;
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
    <html lang="<?= Yii::$app->language ?>" prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb#">
    <head>
        <?= Html::csrfMetaTags() ?>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta property="og:type" content="website" />
        <meta property="og:site_name" content="<?= Yii::$app->controller->siteNameMeta ?>"/>
        <meta property="og:title" content="<?= Yii::$app->controller->titleMeta ?>" />
        <meta property="og:site_name" content="<?= Yii::$app->controller->siteNameMeta ?>"/>
        <meta property="og:description" content="<?= Yii::$app->controller->descriptionMeta ?>" />
        <meta property="og:image" content="<?= Yii::$app->controller->imageMeta ?>" />
        <meta property="og:url" content="<?= Yii::$app->controller->urlMeta ?>" />
        <meta property="og:locale" content="<?= Yii::$app->language ?>" />
        <meta property="fb:app_id" content="<?= Yii::$app->controller->appFbIdMeta ?>" />
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
                'brandLabel' => 'Компания Бояр',
                'brandUrl' => [
                    '/main/index'
                ],
                'brandOptions' => [
                    'class' => 'navbar-brand'
                ]
            ]
        );

        $menuItems = [
            [
                'label' => 'Список продуктов',
                'url' => ['/product/index']
            ],
            [
                'label' => 'Как заказать <span class="glyphicon glyphicon-question-sign"></span>',
                'url' => [
                    '#'
                ],
                'linkOptions' => [
                    'data-toggle' => 'modal',
                    'data-target' => '#modal',
                    'style' => 'cursor: pointer; outline: none;'
                ],
            ],
        ];

        if (!Yii::$app->user->isGuest):
            $user = Yii::$app->user->identity;
            /* @var $user common\models\User */
            if($user->profile->imagesOfObjects):
            foreach($user->profile->imagesOfObjects as $one):
                /* @var $one common\models\ImagesOfObject */
                $image = Html::img('/images/'.$one->image->path_small_image, ['style' => 'width: 35px; border: 2px solid #ffffff; border-radius: 3px;']);
            endforeach;
            else:
                $image = '<span class="btn btn-default glyphicon glyphicon-user" style=""></span>';
            endif;
            $menuItems[] = [
                'label' => $image,
                'items' => [
                    '<li class="dropdown-header">'.Yii::$app->user->identity['email'].'</li>',
                    '<li class="divider"></li>',
                    [
                        'label' => 'Профиль пользователя',
                        'url' => Url::to(['/main/profile'])
                    ],
                    [
                        'label' => 'Выход',
                        'url' => Url::to(['/main/logout'])
                    ]
                ],
                'linkOptions' => [
                    'style' => 'margin: 6px 0 0 20px; padding: 0'
                ]
            ];

        else:
            $menuItems[] = [
                'label' => '<span class="btn btn-default glyphicon glyphicon-question-sign" style=""></span>',
                'items' => [
                    '<li class="dropdown-header">Авторизация</li>',
                    '<li class="divider"></li>',
                    [
                        'label' => 'Войти',
                        'url' => Url::to(['/main/login'])
                    ],
                    [
                        'label' => 'Регистрация',
                        'url' => Url::to(['/main/reg'])
                    ]
                ],
                'linkOptions' => [
                    'style' => 'margin: 6px 0 0 20px; padding: 0'
                ]
            ];

        endif;

        echo Nav::widget([
            'items' => $menuItems,
            'activateParents' => true,
            'encodeLabels' => false,
            'options' => [
                'class' => 'navbar-nav navbar-right'
            ]
        ]);

        Modal::begin(
            [
                'size' => "modal-lg",
                'header' => '<h2>Доставка на дом.</h2>',
                'id' => 'modal'
            ]
        );
        echo 'Правила заказа продукции.';
        Modal::end();

        ActiveForm::begin(
            [
                'action' => ['/найти'],
                'method' => 'get',
                'options' => [
                    'class' => 'navbar-form navbar-right'
                ]
            ]
        );
        echo '<div class="input-group input-group-sm">';
        echo Html::input(
            'type: text',
            'search',
            '',
            [
                'placeholder' => 'Найти ...',
                'class' => 'form-control'
            ]
        );
        echo '<span class="input-group-btn">';
        echo Html::submitButton(
            '<span class="glyphicon glyphicon-search"></span>',
            [
                'class' => 'btn btn-success',
                'onClick' => 'window.location.href = this.form.action + "-" + this.form.search.value.replace(/[^\w\а-яё\А-ЯЁ]+/g, "_") + ".html";'
            ]
        );
        echo '</span></div>';
        ActiveForm::end();

        NavBar::end();

        if(Yii::$app->controller->id == 'main' && Yii::$app->controller->action->id == 'index'):
            ?>
            <?= $content ?>
            <div class="container">
                <?= Alert::widget() ?>
            </div>
            <?php
        else:
            ?>
            <div class="container">
                <?= Alert::widget() ?>
                <?= $content ?>
            </div>
            <?php
        endif;
        ?>
    </div>

    <footer class="footer" style="background-color: #337ab7; max-height: 100%;">
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