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
        $duePayments = CardsRepository::getAllDebts();
        foreach ($duePayments as $payment) {

            $datePayment = new \DateTime($payment['date_payment']);
            $datePayment->setTime(0, 0);

            $today = new \DateTime();
            $today->setTime(0, 0);

            $diff = $today->diff($datePayment);

            if ($diff->invert || $diff->days <= 1) {
                Yii::$app->mailer->compose('/emails/payment-reminder', ['payment' => $payment])
                    ->setFrom("info@deb-track-innovations.ru")
                    ->setTo($payment['email'])
                    ->setSubject('Напоминание о внесение платежа')
                    ->send();
            }
        }
    }
}