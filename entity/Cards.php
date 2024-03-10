<?php

namespace app\entity;
use yii\db\ActiveRecord;

class Cards extends ActiveRecord
{
    public $debt;
    public $start_date;
    public $end_date;
    public $date_payment;
    public $actual_withdrawal_limit;
//    public $name_card;
    public $email;

    public function getBank()
    {
        return $this->hasOne(Banks::class, ['id' => 'bank_id']);
    }

    public function getBalances()
    {
        return $this->hasMany(Balance::class, ['card_id' => 'id']);
    }

    public function getLastBalance()
    {
        return $this->hasOne(Balance::class, ['card_id' => 'id'])->orderBy(['date' => SORT_DESC]);
    }

    public function getPayments()
    {
        return $this->hasMany(Payments::class, ['card_id' => 'id']);
    }
}