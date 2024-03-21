<?php

namespace app\entity;
use yii\db\ActiveRecord;

class Cards extends ActiveRecord
{

    private $debt;
    private $start_date;
    private $end_date;
    private $date_payment;
    private $actual_withdrawal_limit;
    private $email;

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

    public function setDebt($value)
    {
        $this->debt = $value;
    }

    public function getDebt()
    {
        return $this->debt;
    }

    public function setStart_Date($value)
    {
        $this->start_date = $value;
    }

    public function getStart_Date()
    {
        return $this->start_date;
    }

    public function setEnd_Date($value)
    {
        $this->end_date = $value;
    }

    public function getEnd_Date()
    {
        return $this->end_date;
    }

    public function setDate_Payment($value)
    {
        $this->date_payment = $value;
    }

    public function getDate_Payment()
    {
        return $this->date_payment;
    }

    public function setActual_Withdrawal_Limit($value)
    {
        $this->actual_withdrawal_limit = $value;
    }

    public function getActual_Withdrawal_Limit()
    {
        return $this->actual_withdrawal_limit;
    }

    public function setEmail($value)
    {
        $this->email = $value;
    }

    public function getEmail()
    {
        return $this->email;
    }


}