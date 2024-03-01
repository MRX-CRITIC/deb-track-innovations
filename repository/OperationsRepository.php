<?php

namespace app\repository;

use app\entity\Operations;
use yii\data\ActiveDataProvider;

class OperationsRepository
{
    public static function findOperationById($id)
    {
        return Operations::find()
            ->where(['id' => $id])
            ->one();
    }

    public static function getDateLastOperation($user_id, $card_id)
    {
        return Operations::find()
            ->where(['user_id' => $user_id, 'card_id' => $card_id, 'status' => 1])
            ->orderBy(['date_operation' => SORT_DESC])
            ->select('date_operation')
            ->one();
    }

    public static function getAllOperations($user_id)
    {
        return Operations::find()
            ->joinWith(['card.bank'])
            ->where(['operations.user_id' => $user_id, 'operations.status' => 1])
            ->select([
                '{{operations}}.*', // Выбрать все поля из операций
                '{{cards}}.name_card', // Добавить name_card из таблицы карт
                '{{banks}}.name AS bank_name', // Добавить name из таблицы банков как bank_name
            ])
            ->orderBy([
                'operations.date_operation' => SORT_DESC,
                'operations.date_recording' => SORT_DESC
            ])
            ->all();
    }

//    public static function getAllOperations($user_id)
//    {
//        $query = Operations::find()
//            ->joinWith(['card.bank'])
//            ->where(['operations.user_id' => $user_id, 'operations.status' => 1])
//            ->select([
//                '{{operations}}.*', // Выбрать все поля из операций
//                '{{cards}}.name_card', // Добавить name_card из таблицы карт
//                '{{banks}}.name AS bank_name', // Добавить name из таблицы банков как bank_name
//            ])
//            ->orderBy([
//                'operations.date_operation' => SORT_DESC,
//                'operations.date_recording' => SORT_DESC
//            ]);
//
//
//        return new ActiveDataProvider([
//            'query' => $query,
//            'pagination' => [
//                'pageSize' => 10,
//            ],
//            'sort' => [
//                'attributes' => [
//                'date_operation' => [
//                    'asc' => ['operations.date_operation' => SORT_ASC],
//                    'desc' => ['operations.date_operation' => SORT_DESC],
//                    'default' => SORT_DESC,
//                ],
//                'date_recording' => [
//                    'asc' => ['operations.date_recording' => SORT_ASC],
//                    'desc' => ['operations.date_recording' => SORT_DESC],
//                    'default' => SORT_DESC,
//                ],
//            ],
//            ],
//        ]);
//    }

    public static function createOperation(
        $user_id, $card_id, $date_operation,
        $type_operation, $sum, $note = null, $status = 1
    )
    {
        $operation = new Operations();

        $operation->user_id = $user_id;
        $operation->card_id = $card_id;
        $operation->date_operation = $date_operation;
        $operation->type_operation = $type_operation;
        $operation->sum = $sum;
        $operation->note = $note;
        $operation->status = $status;

        $operation->save();
        return $operation->id;
    }

    public static function deleteOperation($id, $user_id)
    {
        $operation = Operations::findOne(['id' => $id, 'user_id' => $user_id]);

        if ($operation !== null) {
            $operation->status = 0;
            $operation->date_status = new \yii\db\Expression('NOW()');

            if ($operation->save()) {
                return true;
            } else {
                return $operation->getErrors();
            }

        } else {
            return 'Операция не найдена';
        }
    }


}