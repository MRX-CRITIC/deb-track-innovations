<?php

namespace app\controllers;

use Exception;
use app\entity\Cards;
use app\repository\BalanceRepository;
use app\repository\BanksRepository;
use app\repository\CardsRepository;
use app\repository\OperationsRepository;
use app\repository\PaymentsRepository;
use app\services\OperationsServices;
use DateInterval;
use DateTime;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

use yii\helpers\FormatConverter;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['about'],
                        'allow' => true,
                        'roles' => ['?', '@'],
                    ],
                    [
                        'actions' => ['index', 'contact', 'operations'],
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
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }


    /**
     * @throws \Exception
     */
    public function actionIndex()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect('/site/about');
        }
        $user_id = Yii::$app->user->getId();
        $cards = CardsRepository::getAllCards($user_id);


//        if ($card->credit_limit > $card->lastBalance->fin_balance) {
//
//        }

        $x = CardsRepository::getNextPayment();
        var_dump($x[1]);

//      var_dump($this->rr($x));



        return $this->render('index', [
            'cards' => $cards,
        ]);
    }

//    public function rr($x)
//    {
//        $processedPayments = [];
//
//        foreach ($x as $operation) {
//            $cardId = $operation['card_id'];
//            $datePayment = $operation['date_payment'];
//            $totalSum = $operation['totalSum'];
//
//            if (!isset($processedPayments[$cardId])) {
//                $processedPayments[$cardId] = [
//                    'payments' => [],
//                    'total_paid' => 0,
//                ];
//            }
//
//            if ($totalSum < 0) { // Если это долг, добавляем его к "должно быть оплачено"
//                $processedPayments[$cardId]['payments'][] = [
//                    'date' => $datePayment,
//                    'amount' => $totalSum
//                ];
//            } else { // Если это платеж, погашаем долг
//                foreach ($processedPayments[$cardId]['payments'] as &$paymentInfo) {
//                    if ($totalSum <= 0) break;
//
//                    if ($paymentInfo['amount'] < 0) { // Есть долг для погашения
//                        if (abs($paymentInfo['amount']) <= $totalSum) {
//                            // Долг полностью погашен
//                            $totalSum += $paymentInfo['amount']; // Вычитаем из суммы платежа
//                            $paymentInfo['amount'] = 0; // Долг погашен
//                        } else {
//                            // Частичное погашение долга
//                            $paymentInfo['amount'] += $totalSum;
//                            $totalSum = 0;
//                        }
//                    }
//                }
//                unset($paymentInfo); // Очистка ссылки
//
//                $processedPayments[$cardId]['total_paid'] += $totalSum; // Обновляем общую сумму платежей после погашения долгов
//            }
//        }
//
//        return $processedPayments;
//    }

    public function actionOperations()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect('/site/about');
        }

        $user_id = Yii::$app->user->getId();
        $operations = OperationsRepository::getAllOperations($user_id);

        return $this->render('operations', [
            'operations' => $operations,
        ]);
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
}
