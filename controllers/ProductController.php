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
use app\services\BalanceServices;
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
            $model->interest_free_period = preg_replace('/\D/', '', $model->interest_free_period);


            $credit_limit = $model->credit_limit;
            $card_id = CardsRepository::createCard(
                $model->user_id,
                $model->bank_id,
                $model->name_card,
                $model->credit_limit,
                $model->cost_banking_services,
                $model->interest_free_period,
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

            $fin_balance = BalanceServices::updateBalance(
                $model->user_id,
                $model->card_id,
                $model->type_operation,
                $model->sum,
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