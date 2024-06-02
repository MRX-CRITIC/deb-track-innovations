<?php

namespace app\services\site;

use app\repository\CardsRepository;
use Exception;
use Yii;

class IndexServices
{
    /**
     * @throws Exception
     */

    /* напоминание об оплате:
        напоминает пользователю о внесение за 5 дней и до тех пор, пока платеж не будет внесен */
    public static function paymentReminder($user_id)
    {
        $difference = '+5 day';

        // получает текущею дату для определения долгов
        $today = new \DateTime();
        $today->setTime(0, 0);

        // отправляем дату и интервал к дате (дата + интервал) для получения долгов на эту дату
        $duePayments = CardsRepository::getAllDebts($today, $difference);

        if (is_array($duePayments) && !empty($duePayments)) {
            foreach ($duePayments as $payment) {
                if ($payment['user_id'] == $user_id) {

                    // преобразуем дату каждого платежа в нужный формат
                    $datePaymentString = $payment['date_payment'];
                    $datePayment = new \DateTime($datePaymentString);

                    /* вычисляем временную разницу между датой платежа и текущей даты
                    прибавляя 1 для захвата последнего дня */
                    $interval = $today->diff($datePayment);
                    $days = $interval->days + 1;

                    /* проверка наступления платежа, в случае если платеж не настал,
                     то дни до внесения положительны */
                    if ($datePayment < $today) {
                        $days = -$days;
                    }

                    // для каждой карты создаем ключ с персональным уведомлением
                    $uniqueKey = 'warning_' . $payment['card_id'];

                    if ($days == 1) {
                        $message = 'Внесите платеж сегодня';
                    } elseif ($days <= 0) {
                        $message = 'Срочно внесите платеж';
                    } else {
                        $message = 'Внесите платеж в течение ' . $days . ' дней';
                    }
                    // отправляем уведомление в сессию
                    Yii::$app->session->setFlash($uniqueKey, $message);
                }
            }
        }
    }

    public static function AllTotalDebt($cards)
    {
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