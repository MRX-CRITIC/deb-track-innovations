<?php

namespace app\commands;

use app\repository\CardsRepository;
use Yii;
use yii\console\Controller;
use yii\console\ExitCode;

class AlertController extends Controller
{
    public static function actionSendPaymentReminders()
    {
        $today = new \DateTime();
        $today->setTime(0, 0);

        $duePayments = CardsRepository::getAllDebts();
        foreach ($duePayments as $payment) {
            if ($payment['date_payment'] - $today >= 1) {
                Yii::$app->mailer->compose('/emails/payment-reminder', ['payment' => $payment])
                    ->setFrom('money.back.monitoring@gmail.com')
                    ->setTo($payment['email'])
                    ->setSubject('Напоминание о внесение платежа')
                    ->send();
            }
        }
    }
}