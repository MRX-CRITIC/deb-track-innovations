<?php

namespace app\controllers;

use app\entity\Balance;
use app\entity\Banks;
use app\entity\Operation;
use app\models\BalanceForm;
use app\models\BanksForm;
use app\models\CardsForm;
use app\models\OperationForm;
use app\repository\BalanceRepository;
use app\repository\BanksRepository;
use app\repository\CardsRepository;
use app\repository\OperationRepository;
use app\repository\UserRepository;
use DateTime;
use Yii;
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
                        'actions' => ['add-card', 'add-bank', 'add-operation'],
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

    public function actionAddCard()
    {
        $cardModel = new CardsForm();
        $balanceModel = new BalanceForm();

        $user_id = Yii::$app->user->getId();
        $cardModel->user_id = $user_id;

        $banksList = BanksRepository::getAllBanks($user_id);
        $banksList['add-bank'] = 'Добавить свой банк... ( + )';

        if (Yii::$app->request->isAjax && $cardModel->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($cardModel);
        }

        if ($cardModel->load(Yii::$app->request->post()) && $cardModel->validate()) {
            $cardModel->interest_free_period = preg_replace('/\D/', '', $cardModel->interest_free_period);


            $credit_limit = $cardModel->credit_limit;
            $card_id = CardsRepository::createCard(
                $cardModel->user_id,
                $cardModel->bank_id,
                $cardModel->name_card,
                $cardModel->credit_limit,
                $cardModel->cost_banking_services,
                $cardModel->interest_free_period,
                $cardModel->payment_partial_repayment,
                $cardModel->percentage_partial_repayment,
                $cardModel->payment_date_purchase_partial_repayment,
                $cardModel->conditions_partial_repayment,
                $cardModel->service_period,
                $cardModel->date_annual_service,
                $cardModel->refund_cash_calculation,
                $cardModel->start_date_billing_period,
                $cardModel->end_date_billing_period,
                $cardModel->note,
            );

            if (!empty($card_id)) {

                $balanceModel->user_id = $user_id;
                $balanceModel->card_id = $card_id;
                $balanceModel->fin_balance = $credit_limit;

                if ($balanceModel->validate()) {
                    BalanceRepository::createBalance(
                        $balanceModel->user_id,
                        $balanceModel->card_id,
                        $balanceModel->fin_balance,
                    );
                    Yii::$app->session->setFlash('success', 'Карта и баланс успешно созданы');
                    return $this->redirect(['add-card']);
                } else {
                    Yii::$app->session->setFlash('error', 'Валидация баланса не пройдена');
                }
            }

            Yii::$app->session->setFlash(
                'error',
                'Карта создана, но при создании баланса произошла ошибка'
            );
//            Yii::$app->session->setFlash('success', 'Карта успешно добавлена');
            return $this->redirect(['add-card']);
        } else {
            return $this->render('add-card', [
                'model' => $cardModel,
                'balanceModel' => $balanceModel,
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

    public function actionAddOperation(int $card_id)
    {
        $card = CardsRepository::getCardBuId($card_id);
        if ($card === null) {
            Yii::$app->session->setFlash('error', 'Карта не найдена');
            return $this->redirect(['/site/index']);
        }

        $model = new OperationForm();
        $model->user_id = Yii::$app->user->getId();
        $model->card_id = $card->id;

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            OperationRepository::createOperation(
                $model->user_id,
                $model->card_id,
                $model->date_operation,
                $model->type_operation,
                $model->sum,
                $model->note,
            );

            Yii::$app->session->setFlash('success', 'Операция успешно добавлена');
            return $this->refresh();
        } else {
            return $this->render('add-operation', [
                'model' => $model,
            ]);
        }
    }


}