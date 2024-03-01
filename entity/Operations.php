<?php

namespace app\entity;
use yii\db\ActiveRecord;

class Operations extends ActiveRecord
{
    public function getCard()
    {
        return $this->hasOne(Cards::class, ['id' => 'card_id']);
    }
}