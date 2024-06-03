<?php

namespace app\services\product;

use DateTime;
use Yii;

class OperationsServices
{
    public static $lastOperationDate = null;

    /**
     * @throws \Exception
     */
    // настройка расчетного периода под текущую дату
    // возвращает начальную и конечную дату расчетного периода
    public static function adjustPeriodToCurrentDate($startDate, $endDate, $dateOperation)
    {
        $currentDate = new DateTime($dateOperation);
        $startDate = new DateTime($startDate);
        $endDate = new DateTime($endDate);

        // Определяем год и месяц даты операции
        $yearOperation = $currentDate->format('Y');
        $monthOperation = $currentDate->format('m');

        // Корректируем начальную и конечную дату в соответствии с годом и месяцем операции
        $adjustedStartDate = DateTime::createFromFormat('Y-m-d', sprintf('%s-%s-%s', $yearOperation, $monthOperation, $startDate->format('d')));
        $adjustedEndDate = DateTime::createFromFormat('Y-m-d', sprintf('%s-%s-%s', $yearOperation, $monthOperation, $endDate->format('d')));
        $adjustedStartDate->setTime(0, 0);
        $adjustedEndDate->setTime(0, 0);

        // Если конечная дата меньше начальной (что указывает на переход через год), добавляем к конечной дате месяц
        if ($adjustedEndDate < $adjustedStartDate) {
            $adjustedEndDate->modify('+1 month');
        }

        // Если дата операции совпадает с конечной датой, период уже соответствует требованиям
        if ($currentDate == $adjustedStartDate) {
            return [
                'start' => $adjustedStartDate->format('Y-m-d'),
                'end' => $adjustedEndDate->format('Y-m-d')
            ];
        }

        // Если дата операции не попадает в интервал между начальной и конечной датами, корректируем интервал
        if ($currentDate < $adjustedStartDate) {
//            return [$adjustedStartDate , $adjustedEndDate];
            $adjustedStartDate->modify('-1 month');
            $adjustedEndDate->modify('-1 month');
            if ($currentDate > $adjustedEndDate) {
                $adjustedEndDate->modify('+1 month');
            }
        } elseif ($currentDate > $adjustedEndDate) {
            $adjustedStartDate->modify('+1 month');
            $adjustedEndDate->modify('+1 month');
        }

        return [
            'start' => $adjustedStartDate->format('Y-m-d'),
            'end' => $adjustedEndDate->format('Y-m-d'),
        ];
    }


    /**
     * @throws \Exception
     */
    // Tinkoff
    public static function settingDatePayment($endDate, $gracePeriod)
    {
        $endDateTime = new DateTime($endDate);

        $daysInMonth = $endDateTime->format('t');

        $newDayOfMonth = (int)$endDateTime->format('d') + $gracePeriod - 30;

        if ($newDayOfMonth > $daysInMonth) {
            $newDayOfMonth -= 31;
            $endDateTime->modify("+1 month");
        }
        $endDateTime->setDate((int)$endDateTime->format('Y'), (int)$endDateTime->format('m'), $newDayOfMonth);

        return $endDateTime->format('Y-m-d');
    }

    // Кубышка от Tinkoff
    public static function settingDatePayment_2($endDate, $gracePeriod)
    {
        $endDateTime = new DateTime($endDate);

        $daysInMonth = $endDateTime->format('t');

        $newDayOfMonth = (int)$endDateTime->format('d') + $gracePeriod - 1;

        if ($newDayOfMonth > $daysInMonth) {
            $newDayOfMonth -= 31;
            $endDateTime->modify("+1 month");
        }
        $endDateTime->setDate((int)$endDateTime->format('Y'), (int)$endDateTime->format('m'), $newDayOfMonth);

        return $endDateTime->format('Y-m-d');
    }

    // альфа (в работе)
    public static function settingDatePayment_3($endDate, $gracePeriod)
    {
        $endDateTime = new DateTime($endDate);

        $daysInMonth = $endDateTime->format('t');

        $newDayOfMonth = (int)$endDateTime->format('d') + $gracePeriod - 1;

        if ($newDayOfMonth > $daysInMonth) {
            $newDayOfMonth -= 31;
            $endDateTime->modify("+1 month");
        }
        $endDateTime->setDate((int)$endDateTime->format('Y'), (int)$endDateTime->format('m'), $newDayOfMonth);

        return $endDateTime->format('Y-m-d');
    }

}