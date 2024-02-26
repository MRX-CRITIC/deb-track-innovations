<?php

namespace app\entity;
use yii\db\ActiveRecord;

class Cards extends ActiveRecord
{
    public function getBank()
    {
        return $this->hasOne(Banks::class, ['id' => 'bank_id']);
    }
}