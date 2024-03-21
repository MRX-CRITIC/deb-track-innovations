<?php

use yii\db\Migration;

/**
 * Class m240321_215339_add_last_login_to_users_table
 */
class m240321_215339_add_last_login_to_users_table extends Migration
{

    public function safeUp()
    {
        $this->addColumn('{{%users}}', 'last_login', $this->dateTime()->null());
    }

    public function safeDown()
    {
        $this->dropColumn('{{%users}}', 'last_login');
    }

}
