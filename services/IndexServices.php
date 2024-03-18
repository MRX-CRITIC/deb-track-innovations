<?php

namespace app\services;

use app\repository\CardsRepository;
use Yii;

class IndexServices
{
    public static function paymentReminder($user_id) {
        $difference = '+5 day';

        $today = new \DateTime();
        $today->setTime(0, 0);

        $cacheKey = 'duePayments_' . $today->format('Y-m-d');
        $duePayments = Yii::$app->cache->get($cacheKey);

        if ($duePayments === false) {
            $duePayments = CardsRepository::getAllDebts($today, $difference);
            Yii::$app->cache->set($cacheKey, $duePayments, 86400);
        }

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

                if ($days <= 1) {
                    $message = 'Внесите платеж сегодня';
                } else {
                    $message = 'Внесите платеж в течение ' . $days . ' дней';
                }

                Yii::$app->session->setFlash($uniqueKey, $message);
            }
        }
    }
}