<?php

namespace app\repository;

use app\entity\Cards;

class CardsRepository
{
    public static function getCardBuId($id)
    {
        return Cards::find()
            ->where(['id' => $id])
            ->select('id')
            ->one();
//        return $card_id ? (int) $card_id : null;
    }

    public static function getCreditLimitCard($id)
    {
        return Cards::find()
            ->where(['id' => $id])
            ->select('credit_limit')
            ->one();
//        return $card_id ? (int) $card_id : null;
    }


    public static function createCard(
        $user_id, $bank_id, $name_card = null,
        $credit_limit, $cost_banking_services, $interest_free_period,
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
        $card->cost_banking_services = $cost_banking_services;
        $card->interest_free_period = $interest_free_period;
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
            ->joinWith('bank')
            ->where(['cards.user_id' => $user_id])
            ->all();
    }


}