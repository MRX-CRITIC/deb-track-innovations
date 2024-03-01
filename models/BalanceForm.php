<?php

namespace app\models;

use Exception;
use Yii;
use yii\base\Model;

class BalanceForm extends Model
{
    public $user_id;
    public $card_id;
    public $fin_balance;
    public $reason;


    public function rules()
    {
        return [
            [['user_id', 'card_id', 'fin_balance', 'reason'], 'required', 'message' => 'Поле не может быть пустое'],
            [['user_id', 'card_id'], 'integer'],
            ['fin_balance', 'number', 'min' => 0, 'max' => 9999999.99],
            [['reason'], 'string', 'max' => 20, 'tooLong' => 'Должно содержать не более 20 символов'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'user_id' => 'Пользователь',
            'card_id' => 'Карта',
            'fin_balance' => 'Баланс карты',
            'reason' => 'Причина изменения баланса',
        ];
    }

}