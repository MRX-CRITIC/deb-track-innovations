<?php

namespace app\services;

use app\models\PaymentsForm;
use app\repository\CardsRepository;
use app\repository\OperationsRepository;
use app\repository\PaymentsRepository;
use Yii;

class PaymentsServices
{
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

    private static function processBillingPeriod($ballingPeriod, $date_operation, $operation_id)
    {
        $datesBillingPeriod = OperationsServices::adjustPeriodToCurrentDate(
            $ballingPeriod->start_date_billing_period,
            $ballingPeriod->end_date_billing_period,
            $date_operation
        );

        $date_payment = OperationsServices::settingDatePayment(
            $datesBillingPeriod['end'],
            $ballingPeriod->grace_period
        );

        $model = new PaymentsForm();
        $model->setAttributes([
            'operation_id' => $operation_id,
            'start_date_billing_period' => $datesBillingPeriod['start'],
            'end_date_billing_period' => $datesBillingPeriod['end'],
            'date_payment' => $date_payment
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
    }

    private static function handleError($operation_id, $message)
    {
        OperationsRepository::findOperationById($operation_id)->delete();
        Yii::$app->session->setFlash('error', $message);
        return false;
    }
}
