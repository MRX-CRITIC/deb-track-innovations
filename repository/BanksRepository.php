<?php

namespace app\repository;

use app\entity\Banks;
use app\entity\Cards;

class BanksRepository
{
    public static function getBankBuId($bank_id)
    {
        return Banks::find()
            ->where(['id' => $bank_id])
            ->one();
    }

    public static function getAllBanks($id)
    {
        return Banks::find()
            ->select(['name', 'id'])
            ->where(['user_id' => $id])
            ->orWhere(['user_id' => 0])
            ->indexBy('id')
            ->indexBy('id')
            ->orderBy(['name' => SORT_ASC])
            ->column();
    }

    public static function createBank($user_id, $name) {
        $bank = new Banks();

        $bank->user_id = $user_id;
        $bank->name = $name;

        $bank->save();
        return $bank->id;
    }


}