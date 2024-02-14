<?php

namespace app\models;

use app\repository\UserRepository;

class ConfirmationForm extends \yii\base\Model
{
    public $confirmationCode;
    public $email;
    public $password;
    public $repeatPassword;

    public function rules()
    {
        return [
            [['confirmationCode', 'email', 'password', 'repeatPassword'], 'required'],
            [['confirmationCode'], 'integer', 'min' => 1000, 'max' => 9999, 'message' => 'Код должен содержать 4 цифры'],
            ['password', 'string', 'min' => 8, 'tooShort' => 'Пароль должен содержать минимум 8 символов'],
            ['repeatPassword', 'compare', 'compareAttribute' => 'password', 'message' => 'Пароли не совпадают'],
            ['email', 'validateEmail'],
            ['email', 'email', 'message' => 'Некорректный формат адреса электронной почты'],
        ];
    }

    public function validateEmail($attribute, $params) {
        if (!$this->hasErrors()) {
            $user = UserRepository::getUserByLogin($this->email);

            if ($user) {
                $this->addError($attribute, 'Пользователь уже существует!');
            }
        }
    }
}