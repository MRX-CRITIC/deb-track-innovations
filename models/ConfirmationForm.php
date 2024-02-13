<?php

namespace app\models;

use app\repository\UserRepository;

class ConfirmationForm extends \yii\base\Model
{
    public $confirmationCode;
    public $email;
    public $password;

    public function rules()
    {
        return [
            [['confirmationCode', 'email', 'password'], 'required'],
            [['confirmationCode'], 'integer', 'min' => 1000, 'max' => 9999],
        ];
    }
}