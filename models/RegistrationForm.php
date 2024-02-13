<?php

namespace app\models;

use app\repository\UserRepository;

class RegistrationForm extends \yii\base\Model
{
    public $email;
    public $password;
    public $repeatPassword;
    public $verificationCode;

    public function rules()
    {
        return [
            [['email', 'password', 'repeatPassword'], 'required', 'message' => 'Поле не может быть пустое'],
            ['password', 'string', 'min' => 8, 'tooShort' => 'Пароль должен содержать минимум 8 символов'],
            ['repeatPassword', 'compare', 'compareAttribute' => 'password', 'message' => 'Пароли не совпадают'],
            ['email', 'validateEmail'],
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