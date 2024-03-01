<?php

namespace app\services;

use app\repository\CardsRepository;
use Yii;


class CardsServices
{
    public static function addNameCard($user_id, $name_card)
    {
        if (empty($name_card)) {
            $count_card = CardsRepository::getCountCards($user_id);
            return "Кредитная карта " . ($count_card + 1);
        } else {
            return $name_card;
        }

    }
}