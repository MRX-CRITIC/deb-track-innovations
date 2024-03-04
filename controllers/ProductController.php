<?php

namespace app\controllers;


use app\entity\Balance;
use app\entity\Banks;
use app\entity\Operations;
use app\models\BalanceForm;
use app\models\BanksForm;
use app\models\CardsForm;
use app\models\OperationsForm;
use app\models\PaymentsForm;
use app\repository\BalanceRepository;
use app\repository\BanksRepository;
use app\repository\CardsRepository;
use app\repository\OperationsRepository;
use app\repository\PaymentsRepository;
use app\repository\UserRepository;
use app\services\BalanceServices;
use app\services\CardsServices;
use app\services\OperationsServices;
use app\services\PaymentsServices;
use DateTime;
use Yii;
use yii\db\StaleObjectException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\Response;
use yii\widgets\ActiveForm;

class ProductController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['add-card', 'add-bank', 'add-operation', 'delete-operation'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @throws StaleObjectException
     * @throws \Throwable
     */
    public function actionAddCard()
    {
        $model = new CardsForm();

        $user_id = Yii::$app->user->getId();
        $model->user_id = $user_id;

        $banksList = BanksRepository::getAllBanks($user_id);
        $banksList['add-bank'] = 'Добавить свой банк... ( + )';

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->grace_period = preg_replace('/\D/', '', $model->grace_period);

            $name_card = CardsServices::addNameCard($user_id, $model->name_card);

            $credit_limit = $model->credit_limit;
            $card_id = CardsRepository::createCard(
                $model->user_id,
                $model->bank_id,
                $name_card,
                $model->credit_limit,
                $model->withdrawal_limit,
                $model->cost_banking_services,
                $model->grace_period,
                $model->payment_partial_repayment,
                $model->percentage_partial_repayment,
                $model->payment_date_purchase_partial_repayment,
                $model->conditions_partial_repayment,
                $model->service_period,
                $model->date_annual_service,
                $model->refund_cash_calculation,
                $model->start_date_billing_period,
                $model->end_date_billing_period,
                $model->note,
            );

            BalanceServices::createStartBalance($user_id, $card_id, $credit_limit);
            return $this->redirect(['add-card']);
        } else {
            return $this->render('add-card', [
                'model' => $model,
                'banksList' => $banksList,
            ]);
        }
    }

    public function actionAddBank()
    {
        $model = new BanksForm();
        $model->user_id = Yii::$app->user->getId();

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            BanksRepository::createBank(
                $model->user_id,
                $model->name_bank,
            );

            Yii::$app->session->setFlash('success', 'Банк успешно добавлен');
            return $this->redirect(['add-bank']);
        } else {
            return $this->render('add-bank', [
                'model' => $model,
            ]);
        }
    }

    /**
     * @throws \Throwable
     * @throws StaleObjectException
     */
    public function actionAddOperation(int $card_id)
    {
        $model = new OperationsForm();
        $model->user_id = Yii::$app->user->getId();

        $card = CardsRepository::getCardBuId($model->user_id, $card_id);
        if ($card === null) {
            Yii::$app->session->setFlash('error', 'Карта не найдена');
            return $this->redirect(['/site/index']);
        }
        $model->card_id = $card->id;

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $operation_id = OperationsRepository::createOperation(
                $model->user_id,
                $model->card_id,
                $model->date_operation,
                $model->type_operation,
                $model->sum,
                $model->note,
            );


            $billingPeriod = PaymentsServices::addPayment(
                $model->user_id,
                $model->card_id,
                $model->date_operation,
                $operation_id,
                $model->type_operation,
                $model->sum,
            );

            if ($billingPeriod) {
                $balance = BalanceServices::updateBalance(
                    $model->user_id,
                    $model->card_id,
                    $model->type_operation,
                    $model->sum,
                );
            } else {
                return $this->refresh();
            }

            if ($balance) {
                Yii::$app->session->setFlash('success', 'Операция успешно добавлена');
            }

            return $this->refresh();
        } else {
            return $this->render('add-operation', [
                'model' => $model,
            ]);
        }
    }

//    public function actionDeleteOperation($id)
//    {
//        $user_id = Yii::$app->user->getId();
//
//        $operation = OperationsRepository::findOperationById($id);
//        $lastDateOperation = OperationsRepository::getDateLastOperation($user_id, $operation->card->id);
//
//
//        if ($lastDateOperation->date_operation <= $operation->date_operation) {
//            if ($operation && $operation->user_id == $user_id) {
//
//                $fin_balance = BalanceRepository::getBalanceCard($user_id, $operation->card->id);
//
//                if ($operation->type_operation == 1) {
//                    $new_balance = $fin_balance->fin_balance - $operation->sum;
//                    $result = BalanceServices::createBalance($user_id, $operation->card->id, $new_balance);
//
//                } elseif ($operation->type_operation == 0) {
//                    $new_balance = $fin_balance->fin_balance + $operation->sum;
//                    $result = BalanceServices::createBalance($user_id, $operation->card->id, $new_balance);
//                }
//
//                if ($result) {
//                    OperationsRepository::deleteOperation($id, $user_id);
//                    Yii::$app->session->setFlash('success', 'Операция успешно удалена');
//                }
//            } else {
//                Yii::$app->session->setFlash('error', 'У вас нет прав для выполнения этой операции');
//            }
//
//        } else {
//            Yii::$app->session->setFlash('error', '
//            Вы можете удалить только последнею операцию той или иной карты.
//            Если вам нужно удалить старую запись, то вы можете удалять только
//            последовательно в том порядка в котором они добавлялись по карте которая вам нужна.
//            ');
//        }
//        return $this->redirect(['/site/operations']);
//    }

    public function actionDeleteOperation($id)
    {
        $user_id = Yii::$app->user->getId();
        $operation = OperationsRepository::findOperationById($id);

        if (!$operation || $operation->user_id != $user_id) {
            Yii::$app->session->setFlash('error', 'У вас нет прав для выполнения этой операции');
            return $this->redirect(['/site/operations']);
        }

        $lastDateOperation = OperationsRepository::getDateLastOperation($user_id, $operation->card_id);

        if (!$lastDateOperation || $lastDateOperation->date_operation > $operation->date_operation) {
            Yii::$app->session->setFlash('error', '
            Вы можете удалить только последнею операцию по выбранной карты. 
            Если вам нужно удалить старую запись, то вы можете удалять только 
            последовательно в том порядка в котором они добавлялись по карте которая вам нужна.');
            return $this->redirect(['/site/operations']);
        }

        $transaction = Yii::$app->db->beginTransaction();

        try {
            $fin_balance = BalanceRepository::getBalanceCard($user_id, $operation->card->id);

            if ($operation->type_operation == 1) {
                $new_balance = $fin_balance->fin_balance - $operation->sum;
                $reason = 'Отмена пополнения';
                $result = BalanceServices::createBalance($user_id, $operation->card->id, $new_balance, $reason);

            } elseif ($operation->type_operation == 0) {
                $new_balance = $fin_balance->fin_balance + $operation->sum;
                $reason = 'Отмена расхода';
                $result = BalanceServices::createBalance($user_id, $operation->card->id, $new_balance, $reason);
            }

            if ($result) {
                OperationsRepository::deleteOperation($id, $user_id);
                Yii::$app->session->setFlash('success', 'Операция успешно удалена');
                $transaction->commit();
            }
        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::error("Ошибка при удалении операции: " . $e->getMessage());
            Yii::$app->session->setFlash('error', 'Произошла ошибка при удалении операции.');
        }
        return $this->redirect(['/site/operations']);
    }


}