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
    public $withdrawal_limit;
    public $cost_banking_services;
    public $grace_period;
    public $payment_partial_repayment;
    public $percentage_partial_repayment;
    public $payment_date_purchase_partial_repayment;
    public $conditions_partial_repayment;
    public $service_period;
    public $date_annual_service;
    public $refund_cash_calculation;
    public $start_date_billing_period;
    public $end_date_billing_period;
    public $note;

    public function rules(): array
    {
        return [
            [['user_id', 'bank_id', 'credit_limit', 'cost_banking_services', 'grace_period'], 'required', 'message' => 'Поле не может быть пустое'],
            [['payment_partial_repayment', 'service_period', 'refund_cash_calculation'], 'required', 'message' => 'Не может быть не выбрано'],
            [['user_id', 'bank_id'], 'integer'],
            [['credit_limit'], 'integer', 'min' => 1000.00, 'max' => 9999999.99, 'tooSmall' => 'Значение не может быть меньше 1 000.00', 'tooBig' => 'Значение не может быть больше 9 999 999.99'],
            [['withdrawal_limit'], 'integer', 'max' => 9999999.99, 'tooBig' => 'Значение не может быть больше 9 999 999.99'],
            [['cost_banking_services'], 'number', 'min' => 0, 'max' => 9999, 'tooBig' => 'Значение не может быть больше 9 999'],
            [['start_date_billing_period', 'end_date_billing_period', 'date_annual_service'], 'date', 'format' => 'php:Y-m-d'],
            [['name_card'], 'string', 'max' => 30, 'tooLong' => 'Должно содержать не более 30 символов'],
            [['payment_partial_repayment', 'payment_date_purchase_partial_repayment', 'refund_cash_calculation', 'service_period'], 'boolean'],
            [['conditions_partial_repayment', 'note'], 'string', 'max' => 600, 'tooLong' => 'Должно содержать не более 600 символов'],

//            [['percentage_partial_repayment'], 'validatePercentagePartialRepayment'],
            [['percentage_partial_repayment', 'payment_partial_repayment'], 'validatePercentagePartialRepayment'],
            ['grace_period', 'validateGracePeriod'],
            [['date_annual_service', 'service_period'], 'validateDateAnnualServiceRequired'],
            [['start_date_billing_period', 'end_date_billing_period'], 'validateDates'],
            [['start_date_billing_period', 'end_date_billing_period', 'refund_cash_calculation'], 'validateDatesRequired'],
        ];
    }

    /**
     * @throws Exception
     */
    public function validateDates($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $startDate = new DateTime($this->start_date_billing_period);
            $endDate = new DateTime($this->end_date_billing_period);
            $diff = $startDate->diff($endDate)->days;

            if ($endDate < $startDate) {
                $this->addError(
                    'end_date_billing_period',
                    'Дата окончания должна быть после даты начала'
                );
            } elseif ($diff > 31 || $diff < 28) {
                $this->addError(
                    'end_date_billing_period',
                    'Разница между датами должна быть от 28 до 31 дня включительно'
                );
            }
        }
    }

    public function validateDatesRequired($attribute, $params)
    {
        if (!$this->hasErrors()) {
            if ($this->refund_cash_calculation == "1") {
                if (empty($this->start_date_billing_period) || empty($this->end_date_billing_period)) {
                    $this->addError('start_date_billing_period', 'Поле не может быть пустое');
                    $this->addError('end_date_billing_period', 'Поле не может быть пустое');
                }
            }
        }
    }

    public function validateDateAnnualServiceRequired($attribute, $params)
    {
        if (!$this->hasErrors()) {
            if ($this->service_period == "1") {
                if (empty($this->date_annual_service)) {
                    $this->addError('date_annual_service', 'Поле не может быть пустое');
                }
            }
        }
    }

    public function validateGracePeriod($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $this->grace_period = preg_replace('/\D/', '', $this->grace_period);

            if ($this->grace_period < 7 || $this->grace_period > 366) {
                $this->addError($attribute, 'Значение должно быть в диапазоне от 7 дней до 1 года');
            }
        }
    }


    public function validatePercentagePartialRepayment($attribute, $params)
    {
        if (!$this->hasErrors() && $this->payment_partial_repayment == "1") {
            // Удаление всех нецифровых символов и преобразование в число
            $percentValueFiltered = preg_replace('/\D/', '', $this->percentage_partial_repayment);

            // Проверяем, не является ли строка пустой после удаления нецифровых символов
            if (empty($percentValueFiltered)) {
                $this->addError('percentage_partial_repayment', 'Поле не может быть пустое');
                return; // Прерываем функцию, если поле пустое
            }

            // Преобразуем строку в число и делим на 100
            $this->percentage_partial_repayment = $percentValueFiltered / 100;
var_dump($this->percentage_partial_repayment);
            // Проверка диапазона значений
            if ($this->percentage_partial_repayment < 0.02 || $this->percentage_partial_repayment > 0.99) {
                $this->addError('percentage_partial_repayment', 'Значение должно быть в диапазоне от 1% до 99%');
            }
        }
    }


    public function attributeLabels()
    {
        return [
            'user_id' => 'Пользователь',
            'bank_id' => 'Название банка',
            'name_card' => 'Название карты',
            'credit_limit' => 'Кредитный лимит',
            'withdrawal_limit' => 'Лимит снятия без процентов и комиссий',
            'cost_banking_services' => 'Стоимость обслуживания',
            'grace_period' => 'Беспроцентный период',
            'payment_partial_repayment' => 'Нужно ли вносить платежи в счет частичного погашения задолженность',
            'percentage_partial_repayment' => 'Какой процент частичного погашения от суммы долга',
            'payment_date_purchase_partial_repayment' => 'Платеж для частичного погашения рассчитывается с даты покупки/снятия',
            'conditions_partial_repayment' => 'Если нет, то опишите как',
            'service_period' => 'Период обслуживания',
            'date_annual_service' => 'Дата годового обслуживания',
            'refund_cash_calculation' => 'Как производится расчет возврата денежных средств банку',
            'start_date_billing_period' => 'Текущая начальная дата расчетного периода',
            'end_date_billing_period' => 'Текущая конечная дата расчетного периода',
            'note' => 'Примечание',
        ];
    }
}



