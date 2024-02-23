<?php

namespace app\models;

use DateTime;
use Exception;
use Yii;
use yii\base\Model;

class CardsForm extends Model
{
    public $user_id;
    public $bank_id;
    public $name_card;
    public $credit_limit;
    public $cost_banking_services;
    public $interest_free_period;
    public $payment_partial_repayment;
    public $percentage_partial_repayment;
    public $payment_date_purchase_partial_repayment;
    public $conditions_partial_repayment;
    public $service_period;
    public $refund_cash_calculation;
    public $start_date_billing_period;
    public $end_date_billing_period;
    public $note;

    public function rules()
    {
        return [
            [
                [
                    'user_id', 'bank_id', 'credit_limit',
                    'start_date_billing_period', 'end_date_billing_period',
                    'cost_banking_services',
                    'interest_free_period'
                ], 'required', 'message' => 'Поле не может быть пустое'
            ],
            [['payment_partial_repayment', 'service_period', 'refund_cash_calculation'], 'required', 'message' => 'Не может быть не выбрано'],
            [['user_id', 'bank_id', 'service_period'], 'integer'],
            [['cost_banking_services', 'percentage_partial_repayment'], 'number'],
            [['start_date_billing_period', 'end_date_billing_period'], 'date', 'format' => 'php:Y-m-d'],
            [['start_date_billing_period', 'end_date_billing_period'], 'validateDates', 'params' => []],
            [['name_card'], 'string', 'max' => 30],
            [['payment_partial_repayment', 'payment_date_purchase_partial_repayment', 'refund_cash_calculation'], 'boolean'],
            [['conditions_partial_repayment', 'note'], 'safe'],
        ];
    }

    /**
     * @throws Exception
     */
    public function validateDates($start_date_billing_period, $end_date_billing_period)
    {
        if (!$this->hasErrors()) {
            $startDate = $start_date_billing_period;
            $endDate = $end_date_billing_period;
            $diff = $startDate->diff($endDate)->days;

//            Yii::info("
//                Начальная дата: " . $startDate->format('Y-m-d') . ",
//                Конечная дата: " . $endDate->format('Y-m-d') . ",
//                ATTRIBUTE: " . $start_date_billing_period . ",
//                PARAMS: " . $end_date_billing_period . ",
//                Разница в днях: $diff", __METHOD__);

            if ($endDate < $startDate) {
                $this->addError('end_date_billing_period', 'Дата окончания должна быть после даты начала');
            } elseif ($diff > 31 || $diff < 28) {
                $this->addError('start_date_billing_period', 'Разница между датами должна быть от 28 до 31 дня включительно');
            }
        }
    }


    public function attributeLabels(): array
    {
        return [
            'user_id' => 'ID пользователя',
            'bank_id' => 'Название банка',
            'name_card' => 'Название карты',
            'credit_limit' => 'Кредитный лимит',
            'cost_banking_services' => 'Стоимость обслуживания',
            'interest_free_period' => 'Беспроцентный период',
            'payment_partial_repayment' => 'Нужно ли вносить платежи в счет частичного погашения задолженность',
            'percentage_partial_repayment' => 'Какой процент частичного погашения от суммы долга',
            'payment_date_purchase_partial_repayment' => 'Платеж для частичного погашения расчитывается с даты покупки/снятия',
            'conditions_partial_repayment' => 'Если нет, то опишите как',
            'service_period' => 'Период обслуживания',
            'refund_cash_calculation' => 'Как производится расчет возврата денежных средств банку',
            'start_date_billing_period' => 'Начальная дата расчетного периода',
            'end_date_billing_period' => 'Конечная дата расчетного периода',
            'note' => 'Примечание',
        ];
    }
}



