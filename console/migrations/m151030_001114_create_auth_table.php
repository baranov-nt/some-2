<?php
/* Миграция создания таблицы для авторизации через соц сети */
use yii\db\Migration;

class m151030_001114_create_auth_table extends Migration
{
    public function up()
    {
        $this->createTable('auth', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'source' => $this->string(255)->notNull(),
            'source_id' => $this->string(255)->notNull()
        ]);
        $this->addForeignKey('auth_user_id', 'auth', 'user_id', 'user', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropForeignKey('auth_user_id', 'auth');
        $this->dropTable('auth');
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
