<?php

use yii\db\Migration;

class m240226_192422_create_cash_flow_table extends Migration
{

    public function safeUp()
    {
        $this->createTable('cash_flow', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'card_id' => $this->integer()->notNull(),
            'date_operation' => $this->date()->notNull(),
            'type_operation' => $this->boolean()->notNull(),
            'sum' => $this->decimal(7, 2)->notNull(),
            'note' => $this->text(),
        ]);

        $this->addForeignKey(
            'fk-cash_flow-user_id',
            'cash_flow',
            'user_id',
            'users',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-cash_flow-bank_id',
            'cash_flow',
            'card_id',
            'cards',
            'id',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-cash_flow-user_id', 'cash_flow');
        $this->dropForeignKey('fk-cash_flow-bank_id', 'cash_flow');

        $this->dropTable('cash_flow');
    }
}
