<?php

namespace app\services;

use app\models\PaymentsForm;
use app\repository\CardsRepository;
use app\repository\OperationsRepository;
use app\repository\PaymentsRepository;
use DateTime;
use Yii;
use yii\db\StaleObjectException;

class PaymentsServices
{
    /**
     * @throws \Throwable
     * @throws StaleObjectException
     */
    public static function addPayment($user_id, $card_id, $date_operation, $operation_id, $type_operation, $sum)
    {
        if (Yii::$app->user->getId() == $user_id) {

            $debts = CardsRepository::getAllDebtsCard($user_id, $card_id);
            if ($debts === true || $type_operation == 0) {

                $ballingPeriod = CardsRepository::getInfoReturnMoney($user_id, $card_id);

                if (!empty($ballingPeriod->start_date_billing_period) && !empty($ballingPeriod->end_date_billing_period)) {
                    return self::processBillingPeriod($ballingPeriod, $date_operation, $operation_id);

                } elseif (!empty($ballingPeriod->percentage_partial_repayment)) {
                    return self::handleError($operation_id, 'Условия возврата еще не добавлены');

                } elseif ($ballingPeriod->refund_cash_calculation == 0 && empty($ballingPeriod->percentage_partial_repayment)) {
                    return self::processBillingPeriod_2($user_id, $card_id, $ballingPeriod, $date_operation, $operation_id);

                } else {
                    return self::handleError($operation_id, 'Не верные условия возврата, пожалуйста, обратитесь в техническую поддержку');
                }

            } else {
                $returnMoney = intval($sum);
                if (is_array($debts)) {
                    foreach ($debts as $key => $debt) {

                        if ($debt['debt'] < 0 && $returnMoney > 0 && $type_operation == 1) {

                            $neededToClearDebt = abs($debt['debt']);

                            if ($returnMoney <= $neededToClearDebt) {
                                $model = new PaymentsForm();
                                $model->setAttributes([
                                    'operation_id' => $operation_id,
                                    'start_date_billing_period' => $debt['start_date'],
                                    'end_date_billing_period' => $debt['end_date'],
                                    'date_payment' => $debt['date_payment']
                                ]);

                                if ($model->validate()) {
                                    PaymentsRepository::createPayment(
                                        $operation_id,
                                        $model->start_date_billing_period,
                                        $model->end_date_billing_period,
                                        $model->date_payment
                                    );

                                    return true;
                                } else {
                                    Yii::$app->session->setFlash('error', 'Не пройдена валидация');
                                    return false;
                                }
                            } else {
                                return self::handleError($operation_id,
                                    'Сумма операции пополнения не может превышать 
                                    текущую не закрытую задолженность в размере ' . $neededToClearDebt . ' rub. Если у вас есть не 
                                    сколько не закрытых задолженностей, то закрывайте их 
                                    разными операциями');
                            }
                        } else {
                            return self::handleError($operation_id,
                                'Не типичная ошибка. Свяжитесь, пожалуйста, с технической поддержкой');
                        }
                    }
                } else {
                    return self::handleError($operation_id, 'Ошибка: данные о долгах не получены или не являются массивом');
                }
            }
            return true;
        } else {
            return self::handleError($operation_id, 'Пользователь не определен');
        }
    }

    private static function createPaymentModel($operationId, $startDate, $endDate, $datePayment)
    {
        $model = new PaymentsForm();
        $model->setAttributes([
            'operation_id' => $operationId,
            'start_date_billing_period' => $startDate,
            'end_date_billing_period' => $endDate,
            'date_payment' => $datePayment,
        ]);

        if ($model->validate()) {
            PaymentsRepository::createPayment(
                $operationId,
                $startDate,
                $endDate,
                $datePayment
            );
            return true;
        } else {
            Yii::$app->session->setFlash('error', 'Не пройдена валидация');
            return false;
        }
    }

    private static function processBillingPeriod($ballingPeriod, $dateOperation, $operationId)
    {
        $datesBillingPeriod = OperationsServices::adjustPeriodToCurrentDate(
            $ballingPeriod->start_date_billing_period,
            $ballingPeriod->end_date_billing_period,
            $dateOperation
        );

        $datePayment = OperationsServices::settingDatePayment(
            $datesBillingPeriod['end'],
            $ballingPeriod->grace_period
        );

        return self::createPaymentModel(
            $operationId,
            $datesBillingPeriod['start'],
            $datesBillingPeriod['end'],
            $datePayment
        );
    }

    private static function processBillingPeriod_2($userId, $cardId, $ballingPeriod, $dateOperation, $operationId)
    {
        $debts = CardsRepository::getAllDebtsCard($userId, $cardId);
        $dateOperation = new DateTime($dateOperation);

        if (is_array($debts)) {
            $endDate = new DateTime(end($debts)['end_date']);
        } else {
            $endDate = new DateTime('0000-00-00');
        }

        if ($dateOperation >= $endDate) {
            $endDate = OperationsServices::settingDatePayment_2(
                $dateOperation->format('Y-m-d'),
                $ballingPeriod->grace_period
            );
        } else {
            $latestDebt = end($debts);
            $endDate = $latestDebt['end_date'];
            $dateOperation = new DateTime($latestDebt['start_date']);
        }

        return self::createPaymentModel(
            $operationId,
            $dateOperation->format('Y-m-d'),
            $endDate,
            $endDate
        );
    }


    /**
     * @throws StaleObjectException|\Throwable
     */
    private static function handleError($operation_id, $message)
    {
        OperationsRepository::findOperationById($operation_id)->delete();
        Yii::$app->session->setFlash('error', $message);
        return false;
    }
}
