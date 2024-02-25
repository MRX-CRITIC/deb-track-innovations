<?php

namespace app\models;

use Exception;
use Yii;
use yii\base\Model;

class BanksForm extends Model
{
    public $user_id;
    public $name_bank;

    public function rules()
    {
        return [
            [['user_id', 'name_bank'], 'required', 'message' => 'Поле не может быть пустое'],
            [['user_id'], 'integer'],
            [['name_bank'], 'string', 'max' => 30, 'tooLong' => 'Должно содержать не более 30 символов'],

            ['user_id', 'validateUserId'],
        ];
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
            'user_id' => 'ID пользователя',
            'name_bank' => 'Название банка',
        ];
    }
}