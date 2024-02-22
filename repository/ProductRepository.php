<?php

namespace app\repository;

use app\entity\Product;

class ProductRepository
{
    public static function getProductBuId($id)
    {
        return Product::find()
            ->where(['id' => $id])
            ->one();
    }

    public static function createCard(
        $user_id, $bank_id, $name_card,
        $credit_limit, $cost_banking_services, $interest_free_period,
        $payment_partial_repayment, $percentage_partial_repayment, $payment_date_purchase_partial_repayment,
        $conditions_partial_repayment, $service_period, $refund_cash_calculation,
        $start_date_billing_period, $end_date_billing_period, $note
    )
    {
        $card = new Product();

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
        $card->refund_cash_calculation = $refund_cash_calculation;
        $card->start_date_billing_period = $start_date_billing_period;
        $card->end_date_billing_period = $end_date_billing_period;
        $card->note = $note;

        $card->save();
        return $card->id;
    }
}