<?php

namespace app\entity;
use yii\db\ActiveRecord;

class Cards extends ActiveRecord
{
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
}