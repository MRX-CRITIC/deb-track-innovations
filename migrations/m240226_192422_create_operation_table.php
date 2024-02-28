<?php

use yii\db\Migration;

class m240226_192422_create_operation_table extends Migration
{

    public function safeUp()
    {
        $this->createTable('operation', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'card_id' => $this->integer()->notNull(),
            'date_operation' => $this->date()->notNull(),
            'type_operation' => $this->boolean()->notNull(),
            'sum' => $this->decimal(9, 2)->notNull(),
            'note' => $this->text(),
        ]);

        $this->addForeignKey(
            'fk-operation-user_id',
            'operation',
            'user_id',
            'users',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-operation-bank_id',
            'operation',
            'card_id',
            'cards',
            'id',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-operation-user_id', 'operation');
        $this->dropForeignKey('fk-operation-bank_id', 'operation');

        $this->dropTable('operation');
    }
}
