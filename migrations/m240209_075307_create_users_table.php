<?php

use yii\db\Migration;

class m240209_075307_create_users_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('users', [
            'id' => $this->primaryKey(),
//            'username' => $this->string(50)->notNull(),
            'email' => $this->string(100)->notNull()->unique(),
            'password' => $this->string(255)->notNull(),
            'status' => $this->boolean()->defaultValue(false)->notNull(),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('users');
    }
}
