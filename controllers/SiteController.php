<?php

namespace app\controllers;

use app\repository\BalanceRepository;
use app\repository\BanksRepository;
use app\repository\CardsRepository;
use app\repository\OperationsRepository;
use DateInterval;
use DateTime;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

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


        function calculateDueDate($startDateStr)
        {
            $startDate = DateTime::createFromFormat('d.m.Y', $startDateStr);


            $endDate = clone $startDate;
            $endDate->add(new DateInterval('P54D'));

            return $endDate->format('d.m.Y');
        }

        $startDates = [
            "23.10.2023",
            "23.11.2023",
            "23.12.2023",
            "23.01.2024",
            "23.02.2024",
            "23.03.2024",
            "23.04.2024",
            "23.05.2024",
            "23.06.2024",
            "23.07.2024",
            "23.08.2024",
            "23.09.2024",
            "23.10.2024",
            "23.11.2024",
            "23.12.2024",
            "23.01.2025",
            "23.02.2025",
            "23.03.2025",
        ];

        foreach ($startDates as $startDate) {
            $dueDate = calculateDueDate($startDate);
            echo "Вернуть потраченные средства за период, начинающийся с $startDate необходимо до $dueDate\n" . "<br>";
        }

        return $this->render('index', [
            'cards' => $cards,
        ]);
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
