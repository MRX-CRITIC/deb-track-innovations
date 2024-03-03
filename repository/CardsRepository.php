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
            ->with()
            ->where(['user_id' => $user_id, 'id' => $card_id])
            ->select('id')
            ->one();
    }

    public static function getCreditLimitCard($user_id, $card_id)
    {
        return Cards::find()
            ->where(['user_id' => $user_id,'id' => $card_id])
            ->select('credit_limit')
            ->one();
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

        } elseif ($card->refund_cash_calculation == 0 && (!empty($card->payment_partial_repayment)) && $card->payment_date_purchase_partial_repayment == 1) {

            return Cards::find()
                ->where(['user_id' => $user_id, 'id' => $card_id])
                ->select(['id', 'grace_period', 'percentage_partial_repayment'])
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
            ->where(['user_id' =>$user_id, 'id' => $card_id])
            ->one()
            ->delete();
    }


    public static function createCard(
        $user_id, $bank_id, $name_card = null,
        $credit_limit, $withdrawal_limit = null, $cost_banking_services, $grace_period,
        $payment_partial_repayment, $percentage_partial_repayment = null,
        $payment_date_purchase_partial_repayment = null,
        $conditions_partial_repayment = null, $service_period, $date_annual_service = null, $refund_cash_calculation,
        $start_date_billing_period = null, $end_date_billing_period = null, $note = null
    )
    {
        $card = new Cards();

        $card->user_id = $user_id;
        $card->bank_id = $bank_id;
        $card->name_card = $name_card;
        $card->credit_limit = $credit_limit;
        $card->withdrawal_limit = $withdrawal_limit;
        $card->cost_banking_services = $cost_banking_services;
        $card->grace_period = $grace_period;
        $card->payment_partial_repayment = $payment_partial_repayment;
        $card->percentage_partial_repayment = $percentage_partial_repayment;
        $card->payment_date_purchase_partial_repayment = $payment_date_purchase_partial_repayment;
        $card->conditions_partial_repayment = $conditions_partial_repayment;
        $card->service_period = $service_period;
        $card->date_annual_service = $date_annual_service;
        $card->refund_cash_calculation = $refund_cash_calculation;
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

    public static function getCountCards($user_id) {
        return Cards::find()
            ->where(['user_id' => $user_id])
            ->count();
    }



    public static function getNextPayment() {
        $subQueryMaxDate = Balance::find()
            ->alias('b1')
            ->select(['b1.card_id', 'max_date' => 'MAX(b1.date)'])
            ->groupBy('b1.card_id');

        $subQueryLastBalance = Balance::find()
            ->alias('b2')
            ->select(['b2.card_id', 'last_balance' => 'b2.fin_balance'])
            ->innerJoin(['sqm' => $subQueryMaxDate], 'b2.card_id = sqm.card_id AND b2.date = sqm.max_date');

        $cards = Cards::find()
            ->alias('c')
            ->select(['card_id' => 'c.id', 'c.credit_limit', 'current_balance' => 'sq.last_balance'])
            ->leftJoin(['sq' => $subQueryLastBalance], 'c.id = sq.card_id')
            ->where('sq.last_balance < c.credit_limit')
            ->asArray()
            ->all();

        $cardsIds = ArrayHelper::getColumn($cards, 'card_id');

        if (empty($cardsIds)) {
            return "Задолженности нет";
        }

        $nearestDatePayment = Payments::find()
            ->select(new Expression("MAX(date_payment) as nearestDate"))
            ->leftJoin(['op' => 'operations'], 'op.id = payments.operation_id')
            ->where(['in', 'op.card_id', $cardsIds])
            ->groupBy('op.card_id')
            ->asArray()
            ->all();

        if (empty($nearestDatePayment)) {
            return "Платежи по найденным картам отсутствуют";
        }

        $paymentOperationsData = Operations::find()
            ->alias('op')
            ->select([
                'op.card_id',
                'date_payment' => 'p.date_payment',
                'totalSum' => new Expression("SUM(IF(op.type_operation = 1, op.sum, -op.sum))")
            ])
            ->innerJoin(['p' => 'payments'], 'p.operation_id = op.id')
            ->where([
                'and',
                ['op.status' => 1],
                ['in', 'op.card_id', $cardsIds],
                ['between', 'op.date_operation', new Expression('p.start_date_billing_period'), new Expression('p.end_date_billing_period')]
            ])
            ->groupBy(['op.card_id', 'p.date_payment'])
            ->asArray()
            ->orderBy(['p.date_payment' => SORT_ASC])
            ->all();

        $processedPayments = [];

        foreach($paymentOperationsData as $operation) {
            $cardId = $operation['card_id'];
            $datePayment = $operation['date_payment'];
            $totalSum = $operation['totalSum'];

            if (!isset($processedPayments[$cardId])) {
                $processedPayments[$cardId] = [
                    'payments' => [],
                    'total_paid' => 0,
                ];
            }

            if ($totalSum < 0) {
                $processedPayments[$cardId]['payments'][] = [
                    'date' => $datePayment,
                    'amount' => $totalSum
                ];
            } else {
                foreach ($processedPayments[$cardId]['payments'] as &$paymentInfo) {
                    if ($totalSum <= 0) break;

                    if ($paymentInfo['amount'] < 0) {
                        if (abs($paymentInfo['amount']) <= $totalSum) {
                            $totalSum += $paymentInfo['amount'];
                            $paymentInfo['amount'] = 0;
                        } else {
                            $paymentInfo['amount'] += $totalSum;
                            $totalSum = 0;
                        }
                    }
                }
                unset($paymentInfo);
                $processedPayments[$cardId]['total_paid'] += $totalSum;
            }
        }
        return $processedPayments;
    }



}