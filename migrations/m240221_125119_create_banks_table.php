<?php

use yii\db\Migration;

class m240221_125119_create_banks_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('banks', [
            'id' => $this->primaryKey(),
            'name' => $this->string(30)->notNull()->unique(),
            'user_id' => $this->integer()->notNull(),
        ]);

        $this->addForeignKey(
            'fk-banks-user_id',
            'banks',
            'user_id',
            'users',
            'id',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-banks-user_id', 'banks');

        $this->dropTable('banks');
    }
}
