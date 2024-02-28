<?php

namespace app\models;

use app\repository\BalanceRepository;
use app\repository\CardsRepository;
use Exception;
use Yii;
use yii\base\Model;

class OperationForm extends Model
{
    public $user_id;
    public $card_id;
    public $date_operation;
    public $type_operation;
    public $sum;
    public $note;

    public function rules()
    {
        return [
            [['user_id', 'card_id', 'date_operation', 'type_operation', 'sum'], 'required', 'message' => 'Поле не может быть пустое'],
            [['user_id', 'card_id', 'type_operation'], 'integer'],
            [['date_operation'], 'date', 'format' => 'php:Y-m-d'],
            [['sum'], 'number'],
            [['note'], 'string', 'max' => 600, 'tooLong' => 'Должно содержать не более 600 символов'],

            [['user_id', 'sum', 'type_operation'], 'validateBalance'],
        ];
    }

    public function validateBalance($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $balance = BalanceRepository::getBalanceCard($this->user_id, $this->card_id);
            $card = CardsRepository::getCreditLimitCard($this->card_id);

            if (empty($card && $balance)) {
                $this->addError('card_id', 'Карта не найдена');
                Yii::$app->session->setFlash('error', 'Карта не найдена');
                return;
            }

            if ($this->type_operation == 1) {
                if ($this->sum + $balance->fin_balance > $card->credit_limit) {
                    $this->addError('sum', 'Сумма внесения превышает кредитный лимит карты');
                }
            } elseif ($this->type_operation == 0) {
                if ($this->sum > $balance->fin_balance) {
                    $this->addError('sum', 'Сумма операции превышает фактический баланс карты');
                }
            }


        }
    }

    public function attributeLabels()
    {
        return [
            'user_id' => 'Пользователь',
            'card_id' => 'Карта',
            'date_operation' => 'Дата операции',
            'type_operation' => 'Тип операции',
            'sum' => 'Сумма',
            'note' => 'Примечание',
        ];
    }

}