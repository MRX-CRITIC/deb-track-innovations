<?php

namespace app\models;

use app\repository\UserRepository;

class ConfirmationForm extends \yii\base\Model
{
    public $confirmationCode;

    public function rules()
    {
        return [
            [['confirmationCode'], 'required'],
            [['confirmationCode'], 'integer', 'length' => [4]],
        ];
    }
}