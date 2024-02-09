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
            [['confirmationCode'], 'integer', 'min' => 1000, 'max' => 9999],
        ];
    }
}