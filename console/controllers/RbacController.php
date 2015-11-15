<?php
namespace console\controllers;

use yii\helpers\Console;
use yii\console\Controller;
use Yii;

/**
 * Контроль доступа на основе ролей.
 * -----------------------------------------------------------------------------
 * Создание ролей:
 *
 * - Создатель      : может все.
 * - Администратор  : может все.
 * - Редактор       : может добавлять и редактировать контент. Не может управлять пользователями
 * - Премиум        : допольнительные опции для пользователей
 * - Пользователь   : обычный пользователь
 *
 * Создание разрешений:
 *
 * - Использовать премиум
 * - Управлять товарами
 * - Добавлять товары
 * - Редактировать товары
 * - Удалять товары
 * - Управлять пользователями
 * - Просматривать пользователей
 *
 * Создание правила:
 *
 * - Автор
 */
class RbacController extends Controller
{
    /**
     * Инициализация RBAC.
     */
    public function actionInit()
    {
        $auth = Yii::$app->authManager;

        //---------- Правила ----------//

        $rule = new \common\rbac\rules\AuthorRule;
        $auth->add($rule);

        //---------- РАЗРЕШЕНИЯ ----------//

        // добавить "Использовать премиум" разрешение
        $usePremiumContent = $auth->createPermission('Использовать премиум');
        $usePremiumContent->description = 'Позволяет использовать премиум контент';
        $auth->add($usePremiumContent);

        // добавить "Управлять товарами" разрешение
        $manageProduct = $auth->createPermission('Управлять товарами');
        $manageProduct->description = 'Позволяет управлять товарами';
        $auth->add($manageProduct);

        // добавить "Добавлять товары" разрешение
        $addProduct = $auth->createPermission('Добавлять товары');
        $addProduct->description = 'Позволяет добавлять товары';
        $auth->add($addProduct);

        // добавить "Редактировать товары" разрешение
        $updateProduct = $auth->createPermission('Редактировать товары');
        $updateProduct->description = 'Позволяет редактировать товары';
        $auth->add($updateProduct);

        // добавить "Удалять товары" разрешение
        $deleteProduct = $auth->createPermission('Удалять товары');
        $deleteProduct->description = 'Позволяет удалять товары';
        $auth->add($deleteProduct);

        // добавить "Управлять пользователями" разрешение
        $manageUsers = $auth->createPermission('Управлять пользователями');
        $manageUsers->description = 'Позволяет управлять пользователями';
        $auth->add($manageUsers);

        // добавить "Управлять пользователями" разрешение
        $viewUsers = $auth->createPermission('Просматривать пользователей');
        $viewUsers->description = 'Позволяет просматривать пользователей';
        $auth->add($viewUsers);

        //---------- РОЛИ ----------//

        // "Пользователь"
        $member = $auth->createRole('Пользователь');
        $member->description = 'Роль. Простой пользователь, зарегистрированный на сайте.';
        $auth->add($member);

        // "Премиум"
        $premium = $auth->createRole('Премиум');
        $premium->description = 'Роль. Премиум пользователь. Который имеет больше возможностей, чем простой пользователь.';
        $auth->add($premium);
        $auth->addChild($premium, $usePremiumContent);

        // "Редактор"
        $editor = $auth->createRole('Редактор');
        $editor->description = 'Роль. Модератор. Управление товарами.';
        $auth->add($editor);
        $auth->addChild($editor, $premium);
        $auth->addChild($editor, $member);
        $auth->addChild($editor, $addProduct);
        $auth->addChild($editor, $updateProduct);
        $auth->addChild($editor, $deleteProduct);
        $auth->addChild($editor, $viewUsers);

        // "Администратор"
        $admin = $auth->createRole('Администратор');
        $admin->description = 'Роль. Администратор. Управление товарами и пользователями.';
        $auth->add($admin);
        $auth->addChild($admin, $editor);
        $auth->addChild($admin, $manageUsers);

        // "Создатель"
        $theCreator = $auth->createRole('Создатель');
        $theCreator->description = 'Роль. Создатель. Управление всем.';
        $auth->add($theCreator); 
        $auth->addChild($theCreator, $admin);

        if ($auth) 
        {
            $this->stdout("\nRbac authorization data are installed successfully.\n", Console::FG_GREEN);
        }
    }
}