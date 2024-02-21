<?php

use yii\db\Migration;

class m240221_125145_create_cards_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('cards', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'bank_id' => $this->integer()->notNull(),
            'credit_limit' => $this->decimal(15, 2)->notNull(),
            'start_date_billing_period' => $this->date()->notNull(),
            'end_date_billing_period' => $this->date()->notNull(),
            'name_card' => $this->string(30)->notNull(),
            'cost_banking_services' => $this->decimal(10, 2)->notNull(),
            'period_cost_banking_services' => $this->integer()->notNull(),
            'interest_free_period' => $this->integer()->notNull(),
            'note' => $this->text(),
        ]);

        $this->addForeignKey(
            'fk-cards-user_id',
            'cards',
            'user_id',
            'users',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-cards-bank_id',
            'cards',
            'bank_id',
            'banks',
            'id',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-cards-user_id', 'cards');
        $this->dropForeignKey('fk-cards-bank_id', 'cards');

        $this->dropTable('cards');
    }


}
