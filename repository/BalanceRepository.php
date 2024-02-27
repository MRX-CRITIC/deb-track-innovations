<?php

namespace app\repository;
use app\entity\Balance;


class BalanceRepository
{
    public static function createBalance ($user_id, $card_id, $fin_balance)
    {
        $balance = new Balance();

        $balance->user_id = $user_id;
        $balance->card_id = $card_id;
        $balance->fin_balance = $fin_balance;

        $balance->save();
        return $balance->id;
    }
}