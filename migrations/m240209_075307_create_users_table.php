<?php

use yii\db\Migration;

class m240209_075307_create_users_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('users', [
            'id' => $this->primaryKey(),
            'email' => $this->string(59)->notNull()->unique(),
            'password' => $this->string(255)->notNull(),
            'confirmation_code' => $this->integer(4)->notNull(),
            'data_registration' => $this->timestamp()->defaultExpression('NOW()')->notNull(),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('users');
    }
}
