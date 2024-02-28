<?php

use yii\db\Migration;

class m240227_123444_create_balance_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('balance', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'card_id' => $this->integer()->notNull(),
            'fin_balance' => $this->decimal(9, 2)->notNull(),
            'date' => $this->timestamp()->defaultExpression('NOW()')->notNull(),
        ]);

        $this->addForeignKey(
            'fk-balance-user_id',
            'balance',
            'user_id',
            'users',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-balance-card_id',
            'balance',
            'card_id',
            'cards',
            'id',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-balance-user_id', 'balance');
        $this->dropForeignKey('fk-balance-card_id', 'balance');

        $this->dropTable('balance');
    }
}
