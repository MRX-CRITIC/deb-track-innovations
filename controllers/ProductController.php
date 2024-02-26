<?php

namespace app\controllers;

use app\entity\Banks;
use app\entity\CashFlow;
use app\models\BanksForm;
use app\models\CardsForm;
use app\models\CashFlowForm;
use app\repository\BanksRepository;
use app\repository\CardsRepository;
use app\repository\CashFlowRepository;
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
//                    [
//                        'actions' => [''],
//                        'allow' => true,
//                        'roles' => ['?'],
//                    ],
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
        $model = new CardsForm();

        $user_id = Yii::$app->user->getId();
        $banksList = BanksRepository::getAllBanks($user_id);
        $banksList['add-bank'] = 'Добавить свой банк... ( + )';

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->interest_free_period = preg_replace('/\D/', '', $model->interest_free_period);
            $model->credit_limit = preg_replace('/\D/', '', $model->credit_limit);

            CardsRepository::createCard(
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

            Yii::$app->session->setFlash('success', 'Карта успешно добавлена');
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

    public function actionAddOperation()
    {
        $model = new CashFlowForm();

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            CashFlowRepository::createOperation(
                $model->user_id,
                $model->card_id,
                $model->date_operation,
                $model->type_operation,
                $model->sum,
                $model->note,
            );

            Yii::$app->session->setFlash('success', 'Операция успешно добавлена');
            return $this->redirect(['add-operation']);
        } else {
            return $this->render('add-operation', [
                'model' => $model,
            ]);
        }
    }


}