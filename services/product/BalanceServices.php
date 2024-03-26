<?php

namespace app\services\product;

use app\models\BalanceForm;
use app\repository\BalanceRepository;
use app\repository\CardsRepository;
use Yii;
use yii\db\StaleObjectException;

class BalanceServices
{
    /**
     * @throws \Throwable
     * @throws StaleObjectException
     */
    public static function createStartBalance($user_id, $card_id, $credit_limit)
    {
        if (!empty($card_id)) {
            $model = new BalanceForm();

            $model->user_id = $user_id;
            $model->card_id = $card_id;
            $model->fin_balance = $credit_limit;
            $model->reason = 'Создание';

            if ($model->validate()) {
                BalanceRepository::createBalance(
                    $model->user_id,
                    $model->card_id,
                    $model->fin_balance,
                    $model->reason,
                );

                Yii::$app->session->setFlash('success', 'Карта успешно создана');
            } else {
                CardsRepository::deleteCardErrorBalance($user_id, $card_id);
                Yii::$app->session->setFlash('error', 'Ошибка! Повторите попытку! Если ошибка сохранилась, пожалуйста, свяжитесь с нами');
            }
        } else {
            Yii::$app->session->setFlash('error', 'Карта не найдена');
        }
    }

    public static function createBalance($user_id, $card_id, $credit_limit, $reason)
    {
        if (!empty($card_id)) {
            $model = new BalanceForm();

            $model->user_id = $user_id;
            $model->card_id = $card_id;
            $model->fin_balance = $credit_limit;
            $model->reason = $reason;

            if ($model->validate()) {
                BalanceRepository::createBalance(
                    $model->user_id,
                    $model->card_id,
                    $model->fin_balance,
                    $model->reason,
                );

                Yii::$app->session->setFlash('success', 'Операция успешно создана');
                return true;
            } else {
                Yii::$app->session->setFlash('error', 'Ошибка! Повторите попытку! Если ошибка сохранилась, пожалуйста, свяжитесь с нами');
                return false;
            }
        } else {
            Yii::$app->session->setFlash('error', 'Карта не найдена');
            return false;
        }
    }


    public static function updateBalance($user_id, $card_id, $type_operation, $sum)
    {
        $fin_balance = BalanceRepository::getBalanceCard($user_id, $card_id);

        if (!empty($fin_balance)) {

            if ($type_operation == 1) {
                $new_balance = $fin_balance->fin_balance + $sum;
                $reason = 'Пополнение';
                BalanceServices::createBalance($user_id, $card_id, $new_balance, $reason);

            } elseif ($type_operation == 0) {
                $new_balance = $fin_balance->fin_balance - $sum;
                $reason = 'Расход';
                BalanceServices::createBalance($user_id, $card_id, $new_balance, $reason);

            } else {
                return false;
            }
        } else {
            return false;
        }
        return $fin_balance;
    }



}