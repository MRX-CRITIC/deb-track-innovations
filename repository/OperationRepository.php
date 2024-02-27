<?php

namespace app\repository;

use app\entity\Operation;

class OperationRepository
{
    public static function getCardBuId($id)
    {
        return Operation::find()
            ->where(['id' => $id])
            ->one();
    }

    public static function createOperation(
        $user_id, $card_id, $date_operation,
        $type_operation, $sum, $note = null
    )
    {
        $operation = new Operation();

        $operation->user_id = $user_id;
        $operation->card_id = $card_id;
        $operation->date_operation = $date_operation;
        $operation->type_operation = $type_operation;
        $operation->sum = $sum;
        $operation->note = $note;

        $operation->save();
        return $operation->id;
    }


}