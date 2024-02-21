<?php

namespace app\models;

use Yii;
use yii\base\Model;
class CardsForm extends Model
{
    public $user_id;
    public $bank_id;
    public $credit_limit;
    public $start_date_billing_period;
    public $end_date_billing_period;
    public $name_card;
    public $cost_banking_services;
    public $period_cost_banking_services;
    public $interest_free_period;
    public $note;
    /**
     * @var mixed
     */

    public function rules()
    {
        return [
            [
                ['user_id', 'bank_id', 'credit_limit',
                'start_date_billing_period', 'end_date_billing_period',
                'name_card', 'cost_banking_services', 'period_cost_banking_services',
                'interest_free_period'], 'required', 'message' => 'Поле не может быть пустое'
            ],
            [['user_id', 'bank_id', 'period_cost_banking_services', 'interest_free_period'], 'integer'],
            [['credit_limit', 'cost_banking_services'], 'number'],
            [['start_date_billing_period', 'end_date_billing_period'], 'date', 'format' => 'php:Y-m-d'],
            [['name_card'], 'string', 'max' => 30],
            [['note'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'user_id' => 'ID пользователя',
            'bank_id' => 'ID банка',
            'credit_limit' => 'Кредитный лимит',
            'start_date_billing_period' => 'Начало периода выписки',
            'end_date_billing_period' => 'Конец периода выписки',
            'name_card' => 'Название карты',
            'cost_banking_services' => 'Стоимость обслуживания',
            'period_cost_banking_services' => 'Периодичность платы за обслуживание',
            'interest_free_period' => 'Беспроцентный период',
            'note' => 'Примечание',
        ];
    }
}