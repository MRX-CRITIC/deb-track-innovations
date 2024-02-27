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
        ];
    }

    public function attributeLabels()
    {
        return [
            'user_id' => 'ID пользователя',
            'name_bank' => 'Название банка',
        ];
    }
}