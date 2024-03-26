<?php

namespace app\repository;

use app\entity\Balance;
use app\entity\Cards;
use app\entity\Operations;
use app\entity\Payments;
use yii\db\Expression;
use yii\db\StaleObjectException;
use yii\helpers\ArrayHelper;

class CardsRepository
{
    public static function getCardBuId($user_id, $card_id)
    {
        return Cards::find()
            ->with(['lastBalance'])
            ->where(['user_id' => $user_id, 'id' => $card_id])
            ->select(['id', 'name_card'])
            ->one();
    }

    public static function getCreditLimitCard($user_id, $card_id)
    {
        return Cards::find()
            ->where(['user_id' => $user_id, 'id' => $card_id])
            ->select('credit_limit')
            ->one();
    }

    public static function getUniqueCardNamesByUserId($user_id)
    {
        return Cards::find()
            ->where(['user_id' => $user_id])
            ->select(['name_card'])
            ->distinct()
            ->all();
    }


    // получаем расчетную информацию и грейс период карты который добавил пользователь
    public static function getInfoReturnMoney($user_id, $card_id)
    {
        $card = Cards::find()->where(['user_id' => $user_id, 'id' => $card_id])->one();

        if (!$card) {
            return null;
        }

        if ($card->refund_cash_calculation == 1) {

            return Cards::find()
                ->where(['user_id' => $user_id, 'id' => $card_id])
                ->select(['id', 'start_date_billing_period', 'end_date_billing_period', 'grace_period'])
//                ->asArray(false)
                ->one();

        } elseif ($card->refund_cash_calculation == 0 && empty($ballingPeriod->percentage_partial_repayment)) {
            /*$card->refund_cash_calculation == 0 &&
            (!empty($card->payment_partial_repayment)) &&
            $card->payment_date_purchase_partial_repayment == 1*/
            return Cards::find()
                ->where(['user_id' => $user_id, 'id' => $card_id])
                ->select(['id', 'grace_period', 'percentage_partial_repayment', 'refund_cash_calculation'])
//                ->asArray(false)
                ->one();

        } else {
            return null;
        }
    }

    /**
     * @throws \Throwable
     * @throws StaleObjectException
     */
    public static function deleteCardErrorBalance($user_id, $card_id)
    {
        return Cards::find()
            ->where(['user_id' => $user_id, 'id' => $card_id])
            ->one()
            ->delete();
    }


    public static function createCard(
        $user_id, $bank_id, $credit_limit, $cost_banking_services, $grace_period,
        $payment_partial_repayment, $service_period, $refund_cash_calculation,
        $name_card = null, $withdrawal_limit = null,
        $percentage_partial_repayment = null, $payment_date_purchase_partial_repayment = null,
        $conditions_partial_repayment = null, $date_annual_service = null,
        $start_date_billing_period = null, $end_date_billing_period = null, $note = null
    )
    {
        $card = new Cards();

        $card->user_id = $user_id;
        $card->bank_id = $bank_id;
        $card->credit_limit = $credit_limit;
        $card->cost_banking_services = $cost_banking_services;
        $card->grace_period = $grace_period;
        $card->payment_partial_repayment = $payment_partial_repayment;
        $card->service_period = $service_period;
        $card->refund_cash_calculation = $refund_cash_calculation;
        $card->name_card = $name_card;
        $card->withdrawal_limit = $withdrawal_limit;
        $card->percentage_partial_repayment = $percentage_partial_repayment;
        $card->payment_date_purchase_partial_repayment = $payment_date_purchase_partial_repayment;
        $card->conditions_partial_repayment = $conditions_partial_repayment;
        $card->date_annual_service = $date_annual_service;
        $card->start_date_billing_period = $start_date_billing_period;
        $card->end_date_billing_period = $end_date_billing_period;
        $card->note = $note;

        $card->save();
        return $card->id;
    }

    public static function getAllCards($user_id)
    {
        return Cards::find()
            ->joinWith(['bank', 'lastBalance'])
            ->where(['cards.user_id' => $user_id])
            ->orderBy(['id' => SORT_ASC])
            ->all();
    }

    public static function getCountCards($user_id)
    {
        return Cards::find()
            ->where(['user_id' => $user_id])
            ->count();
    }


    public static function getAllDebtsCard($user_id, $card_id)
    {
        // Получаем данные об операциях и связанных с ними платежах
        $paymentOperationsData = Operations::find()
            ->alias('op')
            ->select([
                'op.card_id',
                'start_date' => 'p.start_date_billing_period',
                'end_date' => 'p.end_date_billing_period',
                'date_payment' => 'p.date_payment',
                'debt' => new Expression("SUM(IF(op.type_operation = 1, op.sum, -op.sum))")
            ])
            ->innerJoin(['p' => 'payments'], 'p.operation_id = op.id')
            ->where([
                'op.status' => 1,
                'op.card_id' => $card_id,
                'op.user_id' => $user_id,
            ])
            ->having(['!=', 'debt', 0])
            ->groupBy([
                'op.card_id',
                'p.date_payment',
                'p.start_date_billing_period',
                'p.end_date_billing_period'
            ])
            ->asArray()
            ->orderBy(['p.date_payment' => SORT_ASC])
            ->all();

        if (empty($paymentOperationsData)) {
            return true; // "Платежи по данной карте отсутствуют";
        }

        return $paymentOperationsData;
    }

    public static function getAllDebts($today, $difference)
    {
        $tomorrow = (clone $today)->modify($difference);
        $tomorrowStr = $tomorrow->format('Y-m-d');

        $query = Operations::find()
            ->alias('op')
            ->select([
                'op.user_id',
                'op.card_id',
                'c.name_card AS name_card',
                'u.email AS email',
                'p.start_date_billing_period AS start_date',
                'p.end_date_billing_period AS end_date',
                'p.date_payment AS date_payment',
                'debt' => new \yii\db\Expression("SUM(IF(op.type_operation = 1, op.sum, -op.sum))")
            ])
            ->innerJoin('payments p', 'p.operation_id = op.id')
            ->leftJoin('cards c', 'c.id = op.card_id')
            ->leftJoin('users u', 'u.id = op.user_id')
            ->where(['op.status' => 1])
//            ->andWhere(['>=', 'p.date_payment', $todayStr])
            ->andWhere(['<', 'p.date_payment', $tomorrowStr])
            ->having(['!=', 'debt', 0])
            ->groupBy([
                'op.user_id',
                'op.card_id',
                'p.date_payment',
                'p.start_date_billing_period',
                'p.end_date_billing_period'
            ])
            ->orderBy(['op.user_id' => SORT_ASC, 'p.date_payment' => SORT_ASC]);

        $paymentOperationsData = $query->asArray()->all();

        if (empty($paymentOperationsData)) {
            return "Долги отсутствуют";
        }

        return $paymentOperationsData;
    }



    public static function getAllCardsWithDebtsAndPayments($user_id)
    {
        // Под-запрос для получения задолженностей и дат
        $debtSubQuery = (new \yii\db\Query())
            ->select([
                'op.card_id',
                'start_date' => 'p.start_date_billing_period',
                'end_date' => 'p.end_date_billing_period',
                'date_payment' => 'p.date_payment',
                'debt' => new \yii\db\Expression("SUM(IF(op.type_operation = 1, op.sum, -op.sum))"),
            ])
            ->from(['op' => 'operations'])
            ->innerJoin(['p' => 'payments'], 'p.operation_id = op.id')
            ->where([
                'op.status' => 1,
                'op.user_id' => $user_id,
            ])
            ->groupBy([
                'op.card_id',
                'p.start_date_billing_period',
                'p.end_date_billing_period',
                'p.date_payment'
            ])
            ->having(['!=', 'debt', 0]);

        return Cards::find()
            ->alias('c')
            ->joinWith(['bank', 'lastBalance'])
            ->leftJoin(['debtInfo' => $debtSubQuery], 'debtInfo.card_id = c.id')
            ->where(['c.user_id' => $user_id])
            ->select([
                'c.*',
                'debt' => 'debtInfo.debt',
                'start_date' => 'debtInfo.start_date',
                'end_date' => 'debtInfo.end_date',
                'date_payment' => 'debtInfo.date_payment',
            ])
            ->orderBy([
                'c.id' => SORT_ASC,
                'debt' => SORT_ASC,
            ])
            ->all();
    }

    public static function getCardWithDebtsAndPayments($user_id, $card_id)
    {
        // Подзапрос для получения задолженностей и дат
        $debtSubQuery = (new \yii\db\Query())
            ->select([
                'op.card_id',
                'start_date' => 'p.start_date_billing_period',
                'end_date' => 'p.end_date_billing_period',
                'date_payment' => 'p.date_payment',
                'debt' => new \yii\db\Expression("SUM(IF(op.type_operation = 1, op.sum, -op.sum))"),
            ])
            ->from(['op' => 'operations'])
            ->innerJoin(['p' => 'payments'], 'p.operation_id = op.id')
            ->where([
                'op.status' => 1,
                'op.user_id' => $user_id,
            ])
            ->groupBy([
                'op.card_id',
                'p.start_date_billing_period',
                'p.end_date_billing_period',
                'p.date_payment'
            ])
            ->having(['!=', 'debt', 0]);

        return Cards::find()
            ->alias('c')
            ->joinWith(['bank', 'lastBalance'])
            ->leftJoin(['debtInfo' => $debtSubQuery], 'debtInfo.card_id = c.id')
            ->where([
                'c.user_id' => $user_id,
                'c.id' => $card_id, // условие для выборки по определенной карте
            ])
            ->select([
                'c.*',
                'debt' => 'debtInfo.debt',
                'start_date' => 'debtInfo.start_date',
                'end_date' => 'debtInfo.end_date',
                'date_payment' => 'debtInfo.date_payment',
            ])
            ->orderBy([
                'c.id' => SORT_ASC,
                'debt' => SORT_ASC,
            ])
            ->one(); // Используем one(), так как ожидаем получить данные только по одной карте
    }


}