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
            [['email', 'password', 'repeatPassword'], 'required', 'message' => 'hsdbhjsdhgfvs'],
            ['repeatPassword', 'compare', 'compareAttribute' => 'password'],
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