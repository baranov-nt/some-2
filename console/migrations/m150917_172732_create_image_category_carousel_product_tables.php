<?php

use yii\db\Migration;

class m150917_172732_create_image_category_carousel_product_tables extends Migration
{
    /*public function up()
    {

    }

    public function down()
    {
        echo "m150917_172732_create_image_category_carousel_product_tables cannot be reverted.\n";

        return false;
    }*/


    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $this->createTable('product', [                     // таблица товара
            'id' => $this->primaryKey(),
            'images_num' => $this->smallInteger()->defaultValue(3),
            'images_label' => $this->string(32)->defaultValue('product'),
            'name' => $this->string(64),         // название товара
            'desc' => $this->text()->defaultValue(null),             // описание товара
            'price' => $this->decimal(8,2)->defaultValue(0),      // стоимость 1 ед. товара
            'rate' => $this->integer(),                     // рейтинг товара
            'created_at' => $this->integer(),               // время добавления товара
            'updated_at' => $this->integer(),               // время изменения товара
            'category_id' => $this->integer(),              // id категории товара
            'unit_id' => $this->integer(),                  // id ед. измерения товара
            'user_id' => $this->integer(),                  // id, добавишнего товар, пользователя
            'temp' => $this->boolean()->defaultValue('1'),                     // временная запись или нет
        ]);
        $this->createTable('category', [                    // таблица категории заказа
            'id' => $this->primaryKey(),
            'name' => $this->string(11)->notNull(),         // название категории (мясные деликатесы, рыбные деликатесы)
        ]);
        $this->createTable('unit', [                        // таблица для минимальной ед. измерения товара
            'id' => $this->primaryKey(),
            'weight' => $this->integer(),                   // вес (10 гр., 100 гр., 1 кг., нестандартный вес)
            'quantity' => $this->integer(),                 // минимальное количество (штука, вес)
            'unit' => $this->string(11),                    // единица измерения (штука, упаковка, банка, грамм, килограмм)
        ]);

        $this->addForeignKey('product_category', 'product', 'category_id', 'category', 'id');       // связь таблицы product с таблицей category
        $this->addForeignKey('product_unit', 'product', 'unit_id', 'unit', 'id');                   // связь таблицы product с таблицей init
        $this->addForeignKey('product_user', 'product', 'user_id', 'user', 'id');                   // связь таблицы product с таблицей user

        $this->createTable('sale', [                        // таблица для акций и распродажи
            'id' => $this->primaryKey(),
            'percent_sale' => $this->smallInteger(2),       // процент скидки
            'created_at' => $this->integer(),               // время добавления скидки
            'product_id' => $this->integer()->notNull(),    // id продукта для скидки
            'user_id' => $this->integer()->notNull(),       // id пользователя который сделал скидку на продукт
        ]);

        $this->addForeignKey('sale_product', 'sale', 'product_id', 'product', 'id');                // связь таблицы sale с таблицей product
        $this->addForeignKey('sale_user', 'sale', 'user_id', 'user', 'id');                         // связь таблицы sale с таблицей user

        $this->createTable('order', [                       // таблица для заказа
            'id' => $this->primaryKey(),
            'quantity' => $this->integer(),                 // количество заказаной продукции
            'created_at' => $this->integer(),               // время создания заказа
            'updated_at' => $this->integer(),               // время изменения заказа
            'status' => $this->boolean()->notNull(),        // статус заказа
            'product_id' => $this->integer()->notNull(),    // id продукта, который заказали
            'user_id' => $this->integer()->notNull(),       // id пользователя который сделал заказ
        ]);

        $this->addForeignKey('order_product', 'order', 'product_id', 'product', 'id');      // связь таблицы order с таблицей product
        $this->addForeignKey('order_user', 'order', 'product_id', 'user', 'id');            // связь таблицы order с таблицей product

        $this->createTable('rating', [                      // таблица для рейтинга товара
            'id' => $this->primaryKey(),
            'created_at' => $this->integer(),               // время создания заказа
            'product_id' => $this->integer(),               // id продукта, который заказали
            'user_id' => $this->integer(),                  // id пользователя который сделал заказ
        ]);

        $this->addForeignKey('rating_product', 'rating', 'product_id', 'product', 'id');    // связь таблицы rating с таблицей product
        $this->addForeignKey('rating_user', 'rating', 'user_id', 'user', 'id');             // связь таблицы rating с таблицей product

        $this->createTable('carousel', [                    // таблица для карусели на главной странице
            'id' => $this->primaryKey(),
            'images_num' => $this->smallInteger()->defaultValue(1),
            'images_label' => $this->string(32)->defaultValue('carousel'),
            'header' => $this->string(255),                 // название заголовка
            'content' => $this->string(255),                // контент
            'product_id' => $this->integer(),               // id соответствующего товара (если есть, то формируем ссылку на товар)
            'user_id' => $this->integer()->notNull(),       // id пользователя который добавил элемент карусели
            'temp' => $this->boolean()->defaultValue('1'),                     // временная запись или нет
        ]);

        $this->addForeignKey('carousel_product', 'carousel', 'product_id', 'product', 'id');    // связь таблицы carousel с таблицей product
        $this->addForeignKey('carousel_user', 'carousel', 'user_id', 'user', 'id');             // связь таблицы carousel с таблицей product
    }

    public function safeDown()
    {
        $this->dropForeignKey('carousel_user', 'carousel');
        $this->dropForeignKey('carousel_product', 'carousel');

        $this->dropTable('carousel');

        $this->dropForeignKey('rating_user', 'rating');
        $this->dropForeignKey('rating_product', 'rating');

        $this->dropTable('rating');

        $this->dropForeignKey('order_user', 'order');
        $this->dropForeignKey('order_product', 'order');

        $this->dropTable('order');

        $this->dropForeignKey('sale_user', 'sale');
        $this->dropForeignKey('sale_product', 'sale');

        $this->dropTable('sale');

        $this->dropForeignKey('product_user', 'product');
        $this->dropForeignKey('product_unit', 'product');
        $this->dropForeignKey('product_category', 'product');

        $this->dropTable('unit');
        $this->dropTable('category');
        $this->dropTable('product');
    }
}
