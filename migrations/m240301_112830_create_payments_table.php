<?php

use yii\db\Migration;


class m240301_112830_create_payments_table extends Migration
{

    public function safeUp()
    {
        $this->createTable('{{%payments}}', [
            'id' => $this->primaryKey(),
            'operation_id' => $this->integer()->notNull(),
        ]);
    }

    public function safeDown()
    {

    }
}
