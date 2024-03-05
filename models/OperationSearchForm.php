<?php

namespace app\models;

use yii\base\Model;

class OperationSearchForm extends Model
{
    public $name_card;
    public $date_operation;

    public function rules()
    {
        return [
            [['name_card', 'date_operation'], 'safe'],
        ];
    }



    public function attributeLabels()
    {
        return [
            'name_card' => 'Отфильтровать по карте',
            'date_operation' => 'Отфильтровать по дате опарации',
        ];
    }
}

