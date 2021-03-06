<?php
use yii\db\Migration;

class m151018_040326_create_images_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('images', [
                'id' => $this->primaryKey(),
                'path_small_image' => $this->string(255)->notNull(),        // миниатюра изображения и путь к ней
                'path' => $this->string(255)->notNull(),        // изображение и путь к нему
                'size' => $this->integer(),                     // размер изображения
                'status' => $this->boolean(),                   // статус (проверено или нет)
                'created_at' => $this->integer(),               // дата создания изображения
                'updated_at' => $this->integer(),               // дата изменения изображения
                'temp' => $this->boolean(),                     // временный файл или нет
            ]
        );
    }

    public function down()
    {
        $this->dropForeignKey('images_profile', 'images');

        $this->dropTable('images');
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
