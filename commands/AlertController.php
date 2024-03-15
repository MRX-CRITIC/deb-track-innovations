<?php

namespace app\commands;

use app\repository\CardsRepository;
use Exception;
use Yii;
use yii\console\Controller;
use yii\console\ExitCode;

class AlertController extends Controller
{
    /**
     * @throws Exception
     */
    public function actionSendPaymentReminders()
    {
        $today = new \DateTime();
        $today->setTime(0, 0);

        $duePayments = CardsRepository::getAllDebts($today);
        foreach ($duePayments as $payment) {

            Yii::$app->mailer->compose('/emails/payment-reminder', ['payment' => $payment])
                ->setFrom("info@deb-track-innovations.ru")
                ->setTo($payment['email'])
                ->setSubject('Напоминание о внесение платежа')
                ->send();

        }
    }
}