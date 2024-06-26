<?php

namespace app\models;

use app\entity\Users;
use app\repository\UserRepository;
use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 * @property-read Users|null $user
 *
 */
class LoginForm extends Model
{
    public $email;
    public $password;
    public $rememberMe = true;
    public $last_login;
    public $errors;
    private $_user = false;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['email', 'password'], 'required', 'message' => 'Поле не может быть пустое'],
            ['rememberMe', 'boolean'],
            ['password', 'validatePassword'],
            ['last_login', 'date'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Неверный адрес электронной почты или пароль');
            }
        }
//        return parent::validate();
    }

    /**
     * Logs in a user using the provided username and password.
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600*24*30 : 0);
        } else {
            $this->errors = $this->getErrors(); // Записываем ошибки в свойство errors
            return false;
        }
    }

    /**
     * Finds user by [[username]]
     *
     * @return Users|array|\yii\db\ActiveRecord|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = UserRepository::getUserByLogin($this->email);
        }

        return $this->_user;
    }

    public function attributeLabels()
    {
        return [
            'email' => 'Электронная почта',
            'password' => 'Пароль',
            'last_login' => 'Последние время входа',
            'rememberMe' => 'Запомнить меня',
        ];
    }
}
