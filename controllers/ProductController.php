<?php

namespace app\controllers;

use app\models\CardsForm;
use app\repository\ProductRepository;
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
                        'actions' => ['add-card'],
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

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->user_id = Yii::$app->user->getId();
            ProductRepository::createCard(
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
                $model->refund_cash_calculation,
                $model->start_date_billing_period,
                $model->end_date_billing_period,
                $model->note,
            );

            Yii::$app->session->setFlash('success', 'Карточка успешно добавлена.');
//            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('add-card', [
                'model' => $model,
            ]);
        }
    }


}