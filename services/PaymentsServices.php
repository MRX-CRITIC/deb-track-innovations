<?php

namespace app\services;

use app\models\PaymentsForm;
use app\repository\CardsRepository;
use app\repository\OperationsRepository;
use app\repository\PaymentsRepository;
use Yii;

class PaymentsServices
{

    public static function addPayment($user_id, $card_id, $date_operation, $operation_id)
    {
        $ballingPeriod = CardsRepository::getInfoReturnMoney($user_id, $card_id);


        if (!empty($ballingPeriod->start_date_billing_period) && !empty($ballingPeriod->end_date_billing_period)) {
            return self::processBillingPeriod($ballingPeriod, $date_operation, $operation_id);
        } elseif (!empty($ballingPeriod->percentage_partial_repayment)) {
            return self::handleError($operation_id, 'Условия возврата еще не добавлены');
        } else {
            return self::handleError($operation_id, 'Не верные условия возврата, пожалуйста, обратитесь в техническую поддержку');
        }
    }

    private static function processBillingPeriod($ballingPeriod, $date_operation, $operation_id)
    {
        $datesBillingPeriod = OperationsServices::adjustPeriodToCurrentDate(
            $ballingPeriod->start_date_billing_period,
            $ballingPeriod->end_date_billing_period,
            $date_operation
        );

        $date_payment = OperationsServices::settingDatePayment(
            $datesBillingPeriod['end'],
            $ballingPeriod->grace_period
        );

        $model = new PaymentsForm();
        $model->setAttributes([
            'operation_id' => $operation_id,
            'start_date_billing_period' => $datesBillingPeriod['start'],
            'end_date_billing_period' => $datesBillingPeriod['end'],
            'date_payment' => $date_payment
        ]);

        if ($model->validate()) {
            PaymentsRepository::createPayment(
                $operation_id,
                $model->start_date_billing_period,
                $model->end_date_billing_period,
                $model->date_payment
            );
            return true;
        } else {
            Yii::$app->session->setFlash('error', 'Не пройдена валидация');
            return false;
        }
    }

    private static function handleError($operation_id, $message)
    {
        OperationsRepository::findOperationById($operation_id)->delete();
        Yii::$app->session->setFlash('error', $message);
        return false;
    }



}
