<?php

namespace app\services;

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

            if ($model->validate()) {
                BalanceRepository::createBalance(
                    $model->user_id,
                    $model->card_id,
                    $model->fin_balance,
                );

                Yii::$app->session->setFlash('success', 'Карта успешно создана');
            } else {
                CardsRepository::deleteCardErrorBalance($card_id);
                Yii::$app->session->setFlash('error', 'Ошибка! Повторите попытку! Если ошибка сохранилась, пожалуйста, свяжитесь с нами');
            }
        } else {
            Yii::$app->session->setFlash('error', 'Карта не найдена');
        }
    }

    public static function createBalance($user_id, $card_id, $credit_limit)
    {
        if (!empty($card_id)) {
            $model = new BalanceForm();

            $model->user_id = $user_id;
            $model->card_id = $card_id;
            $model->fin_balance = $credit_limit;

            if ($model->validate()) {
                BalanceRepository::createBalance(
                    $model->user_id,
                    $model->card_id,
                    $model->fin_balance,
                );

                Yii::$app->session->setFlash('success', 'Операция успешно создана');
            } else {
                Yii::$app->session->setFlash('error', 'Ошибка! Повторите попытку! Если ошибка сохранилась, пожалуйста, свяжитесь с нами');
            }
        } else {
            Yii::$app->session->setFlash('error', 'Карта не найдена');
        }
    }


    public static function updateBalance($user_id, $card_id, $type_operation, $sum)
    {
        $fin_balance = BalanceRepository::getBalanceCard($user_id, $card_id);

        if (!empty($fin_balance)) {

            if ($type_operation == 1) {
                $new_balance = $fin_balance->fin_balance + $sum;
                BalanceServices::createBalance($user_id, $card_id, $new_balance);

            } elseif ($type_operation == 0) {
                $new_balance = $fin_balance->fin_balance - $sum;
                BalanceServices::createBalance($user_id, $card_id, $new_balance);
            }

        }
        return $fin_balance;
    }

}