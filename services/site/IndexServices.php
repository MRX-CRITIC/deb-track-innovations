<?php

namespace app\services\site;

use app\repository\CardsRepository;
use Yii;

class IndexServices
{
    /**
     * @throws \Exception
     */
    public static function paymentReminder($user_id)
    {
        $difference = '+5 day';

        $today = new \DateTime();
        $today->setTime(0, 0);

        $cacheKey = 'duePayments_' . $today->format('Y-m-d');
        $duePayments = Yii::$app->cache->get($cacheKey);

        if (!$duePayments) {
            $duePayments = CardsRepository::getAllDebts($today, $difference);
            Yii::$app->cache->set($cacheKey, $duePayments, 86400);
        }
//        Yii::$app->cache->delete($cacheKey);

        foreach ($duePayments as $payment) {
            if ($payment['user_id'] == $user_id) {

                $datePaymentString = $payment['date_payment'];
                $datePayment = new \DateTime($datePaymentString);

                $interval = $today->diff($datePayment);
                $days = $interval->days + 1;


                if ($datePayment < $today) {
                    $days = -$days;
                }

                $uniqueKey = 'warning_' . $payment['card_id'];

                if ($days == 1) {
                    $message = 'Внесите платеж сегодня';
                } elseif ($days <= 0){
                    $message = 'Срочно внесите платеж';
                } else {
                    $message = 'Внесите платеж в течение ' . $days . ' дней';
                }
                Yii::$app->session->setFlash($uniqueKey, $message);
            }
        }
    }

    public static function AllTotalDebt($cards) {
        $allTotalDebt = 0;
        if (isset($cards)) {
            foreach ($cards as $card) {
                $totalDebt = $card->credit_limit - $card->lastBalance->fin_balance;
                $allTotalDebt += $totalDebt;
            }
            return $allTotalDebt;
        } else {
            return false;
        }
    }
}