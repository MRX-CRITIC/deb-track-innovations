<?php

namespace app\repository;

use app\entity\Cards;
use yii\db\StaleObjectException;

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

    public static function getBillingAndGracePeriodCard($user_id, $card_id)
    {
        return Cards::find()
            ->where(['user_id' => $user_id,'id' => $card_id])
            ->select('start_date_billing_period, end_date_billing_period, grace_period')
            ->one();
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


}