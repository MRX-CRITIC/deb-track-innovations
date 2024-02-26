<?php

namespace app\models;

use app\repository\CardsRepository;
use Exception;
use Yii;
use yii\base\Model;

class CashFlowForm extends Model
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
            [['user_id', 'card_id', 'date_operation', 'type_operation', 'sum'], 'required'],
            [['user_id', 'card_id', 'type_operation'], 'integer'],
            [['date_operation'], 'date', 'format' => 'php:Y-m-d'],
            [['sum'], 'number'],
            [['note'], 'string', 'max' => 600, 'tooLong' => 'Должно содержать не более 600 символов'],

            ['user_id', 'validateUserId'],
            ['sum', 'validateCreditLimit'],
        ];
    }

    public function validateCreditLimit($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $card = CardsRepository::getCardBuId($this->card_id);

            if (!$card) {
                $this->addError('card_id', 'Карта не найдена');
                Yii::$app->session->setFlash('error', 'Карта не найдена');
            }
            elseif ($this->sum > $card->credit_limit) {
                $this->addError($attribute, 'Сумма операции превышает кредитный лимит карты');
            }
        }
    }

    public function validateUserId($attribute, $params)
    {
        if (!$this->hasErrors()) {
            if ($this->user_id != Yii::$app->user->getId()) {
                $this->addError('user_id', 'Ошибка! Попытка изменить структуру формы');
                Yii::$app->session->setFlash('error', 'Ошибка! Попытка изменить целостность формы');
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