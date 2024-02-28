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
    public static function createStartBalance($user_id, $card_id, $credit_limit) {
        $model = new BalanceForm();

        if (!empty($card_id)) {

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
                Yii::$app->session->setFlash('error', 'Ошибка при создание карты! Повторите попытку! Если ошибка сохранилась, пожалуйста, свяжитесь с нами');
            }
        } else {
            Yii::$app->session->setFlash('error', 'Карта не найдена');
        }
    }
}