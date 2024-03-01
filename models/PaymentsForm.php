<?php

namespace app\models;
use yii\base\Model;

class PaymentsForm extends Model
{
    public $operation_id;
    public $start_date_billing_period;
    public $end_date_billing_period;
    public $date_payment;

    public function rules()
    {
        return [
            [['operation_id', 'date_payment'], 'required', 'message' => 'Поле не может быть пустое'],
            [['operation_id'], 'integer'],
            [['start_date_billing_period', 'end_date_billing_period', 'date_payment'], 'date', 'format' => 'php:Y-m-d'],
        ];
    }


    public function attributeLabels()
    {
        return [
            'operation_id' => 'ID операции',
            'start_date_billing_period' => 'Текущая начальная дата расчетного периода',
            'end_date_billing_period' => 'Текущая конечная дата расчетного периода',
            'date_payment' => 'Дата платежа задолженности',
        ];
    }
}