<?php

namespace app\services;
use DateTime;
use Yii;

class OperationsServices
{

    public static function adjustPeriodToCurrentDate($startDate, $endDate, $dateOperation) {

        $currentDate = new DateTime($dateOperation);

        $startDay = substr($startDate, -2);
        $endDay = substr($endDate, -2);

        $startMonth = $currentDate->format('m');
        $startYear = $currentDate->format('Y');

        $endMonth = $startMonth;
        $endYear = $startYear;

        if ($startDay > $endDay) {
            $endDateTemp = DateTime::createFromFormat(
                'Y-m-d', "$startYear-$startMonth-$endDay"
            )->modify('+1 month');
            $endYear = $endDateTemp->format('Y');
            $endMonth = $endDateTemp->format('m');
        }

        $startDateNew = DateTime::createFromFormat('Y-m-d', "$startYear-$startMonth-$startDay");
        $endDateNew = DateTime::createFromFormat('Y-m-d', "$endYear-$endMonth-$endDay");

        return ['start' => $startDateNew->format('Y-m-d'), 'end' => $endDateNew->format('Y-m-d')];
    }



}