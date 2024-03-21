<?php

namespace app\repository;

use app\entity\Cards;
use app\entity\Operations;
use app\entity\Payments;
use Yii;
use yii\db\Expression;
use yii\db\Query;

class PaymentsRepository
{

    public static function findPaymentById($id)
    {
        return Payments::find()
            ->where(['id' => $id])
            ->one();
    }

    public static function findAllPaymentByOperationId($operation_id)
    {
        return Payments::find()
            ->where(['id' => $operation_id])
            ->all();
    }

    public static function createPayment(
        $operation_id, $start_date_billing_period,
        $end_date_billing_period, $date_payment
    )
    {
        $payment = new Payments();

        $payment->operation_id = $operation_id;
        $payment->start_date_billing_period = $start_date_billing_period;
        $payment->end_date_billing_period = $end_date_billing_period;
        $payment->date_payment = $date_payment;


        $payment->save();
        return $payment->id;
    }

    // проверяет есть ли у операции которую хочет добавить пользователь расчетный период в бд
    // возвращает true or false
    public static function checkPaymentPeriodExists($date_operation, $card_id)
    {
        $payments = Payments::find()
            ->innerJoin('operations', 'payments.operation_id = operations.id')
            ->where(['operations.card_id' => $card_id])
            ->andWhere(['<=', 'payments.start_date_billing_period', $date_operation])
            ->andWhere(['>=', 'payments.end_date_billing_period', $date_operation])
            ->all();

        return !empty($payments);
    }

}