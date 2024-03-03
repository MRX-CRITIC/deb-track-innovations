<?php

namespace app\repository;

use app\entity\Cards;
use app\entity\Payments;
use Yii;
use yii\db\Expression;

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
    public static function   checkPaymentPeriodExists($date_operation, $card_id)
    {
        $payments = Payments::find()
            ->innerJoin('operations', 'payments.operation_id = operations.id')
            ->where(['operations.card_id' => $card_id])
            ->andWhere(['<=', 'payments.start_date_billing_period', $date_operation])
            ->andWhere(['>=', 'payments.end_date_billing_period', $date_operation])
            ->all();

        return !empty($payments);
    }



//    public static function checkAndProcessPaymentPeriod($card_id, $operation_id, $date_operation)
//    {
//        $card = Cards::findOne($card_id);
//        if (!$card) {
//            return false;
//        }
//
//        $currentMonth = date('Y-m', strtotime($date_operation));
//        $payments = Payments::find()->where(['operation_id' => $operation_id])->one();
//
//        if (!$payments) {
//            // Создаем новый период оплаты
//            static::createPaymentPeriod($card, $operation_id, $currentMonth);
//        } else {
//            // Проверяем, попадает ли date_operation в текущий период оплаты
//            if (strtotime($date_operation) >= strtotime($payments->start_date_billing_period) && strtotime($date_operation) <= strtotime($payments->end_date_billing_period)) {
//                // Операция попадает в текущий период
//                return true;
//            } else if (strtotime($date_operation) > strtotime($payments->end_date_billing_period)) {
//                // Создаем новый период оплаты
//                static::createPaymentPeriod($card, $operation_id, $currentMonth);
//            } else {
//                // Операция не попадает в период
//                Yii::$app->session->setFlash('error', 'Дата операции попадает в прошедший период или не существующий период - Операция не добавлена');
//                return false;
//            }
//        }
//    }
//
//    protected static function createPaymentPeriod($card, $operation_id, $currentMonth, $gracePeriod = 0)
//    {
//        $start = new Expression("STR_TO_DATE('{$currentMonth}-{$card->start_date_billing_period}', '%Y-%m-%d')");
//        $end = new Expression("STR_TO_DATE('{$currentMonth}-{$card->end_date_billing_period}', '%Y-%m-%d')");
//        $datePayment = new Expression("DATE_ADD(STR_TO_DATE('{$currentMonth}-{$card->end_date_billing_period}', '%Y-%m-%d'), INTERVAL " . ($gracePeriod - 30) . " DAY)");
//
//        $payment = new Payments();
//
//        $payment->operation_id = $operation_id;
//        $payment->start_date_billing_period = $start;
//        $payment->end_date_billing_period = $end;
//        $payment->date_payment = $datePayment;
//
//        $payment->save();
//        return $payment->id;
//    }



}