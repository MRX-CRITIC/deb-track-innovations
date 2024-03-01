<?php

use yii\db\Migration;


class m240301_112830_create_payments_table extends Migration
{

    public function safeUp()
    {
        $this->createTable('{{%payments}}', [
            'id' => $this->primaryKey(),
            'operation_id' => $this->integer()->notNull(),
            'start_date_billing_period' => $this->date(),
            'end_date_billing_period' => $this->date(),
            'date_payment' => $this->date(),
        ]);

        $this->addForeignKey(
            'fk-payments-operation_id',
            'payments',
            'operation_id',
            'operations',
            'id',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-payments-operation_id', 'payments');

        $this->dropTable('payments');
    }
}
