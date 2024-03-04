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
                        'actions' => ['index', 'contact', 'operations', 'test'],
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
        $cards = CardsRepository::getAllCardWithDebtsAndPayments($user_id);


        return $this->render('index', [
            'cards' => $cards,
        ]);
    }

    public function actionTest() {


//        $x = CardsRepository::getAllDebtsCard(4, 6);
//        var_dump($x);

//        $debts = CardsRepository::getAllDebtsCard(4, 1);
        $user_id = Yii::$app->user->getId();

        $cards = CardsRepository::getAllCardWithDebtsAndPayments($user_id);
//        $debts = CardsRepository::getAllDebtsCard($user_id, 1);

        var_dump($cards);

//        var_dump($debts);
//        1) $debt['debt'] = -110000;
//        2) $debt['debt'] = -6200;
//        3) $debt['debt'] = -500;

//        $returnMoney = intval('111000');
//
//        if(is_array($debts)) {
//            foreach ($debts as $key => $debt) {
//                if ($debt['debt'] < 0 && $returnMoney > 0) {
//                    $neededToClearDebt = abs($debt['debt']);
//
//                    if ($returnMoney >= $neededToClearDebt) {
//                        $debts[$key]['debt'] = 0;
//                        $returnMoney -= $neededToClearDebt;
//                    } else {
//                        $debts[$key]['debt'] += $returnMoney;
//                        $returnMoney = 0; // Все деньги возвращены.
//                        break; // Выходим из цикла, т.к. денег больше нет.
//
//                    }
//                }
//            }
//
//        } else {
//            echo "Ошибка: данные о долгах не получены или не являются массивом.";
//        }



// Если после цикла вам важно знать, остались ли непогашенные долги или остаток возврата, можно проверить и использовать эти данные.
//        $t = CardsRepository::getAllDebtsCard(4, 7);
//        var_dump($t);
//
//        $e = CardsRepository::getAllDebtsCard(4, 8);
//        var_dump($e);


    }

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
