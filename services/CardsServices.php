<?php

namespace app\services;

use app\repository\CardsRepository;
use Yii;
use yii\base\InvalidConfigException;


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

    /**
     * @throws InvalidConfigException
     */
    public static function actualWithdrawalLimit($cards) {

        $today = Yii::$app->formatter->asDate('now', 'php:Y-m-d');

        foreach ($cards as $id => $card) {
            if (!empty($card->end_date)) {
                if ($card->end_date < $today) {
                    if ($card->lastBalance->fin_balance < $card->credit_limit && $card->lastBalance->fin_balance != 0) {
                        $cards[$id]->actual_withdrawal_limit = $card->lastBalance->fin_balance;
                    } else {
                        $cards[$id]->actual_withdrawal_limit = 0;
                    }
                } elseif ($card->start_date < $today && $card->end_date > $today) {
                    $cards[$id]->actual_withdrawal_limit = $card->withdrawal_limit + $card->debt;
                }
            } elseif ($card->lastBalance->fin_balance == $card->credit_limit) {
                $cards[$id]->actual_withdrawal_limit = $card->withdrawal_limit;
            } else {
                $cards[$id]->actual_withdrawal_limit = 'error';
            }
        }
        return $cards;
    }
}