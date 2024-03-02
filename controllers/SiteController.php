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


        // Предполагаем, что $x - это объект, возвращенный из CardsRepository
// и содержащий информацию об окончании периода счетов и льготном периоде
        $x = CardsRepository::getBillingAndGracePeriodCard(4, 1);

// Создаем объект DateTime для начальной даты
        $endDate = new DateTime($x->end_date_billing_period);

// Получаем количество дней в месяце начальной даты
        $daysInMonth = $endDate->format('t');

// Вычисляем новый день месяца после добавления grace_period
        $newDayOfMonth = (int)$endDate->format('d') + $x->grace_period-30;

        if ($newDayOfMonth > $daysInMonth) {
            // Если результат выходит за пределы количества дней в месяце, корректируем дату
            $newDayOfMonth -= 31; // Вычитаем 31 для корректировки
            $endDate->modify("+1 month"); // Добавляем один месяц к текущей дате
            $endDate->setDate((int)$endDate->format('Y'), (int)$endDate->format('m'), $newDayOfMonth);
        } else {
            // Если число меньше или равно количеству дней в месяце, просто устанавливаем новый день месяца
            $endDate->setDate((int)$endDate->format('Y'), (int)$endDate->format('m'), $newDayOfMonth);
        }

        echo $endDate->format('Y-m-d'); // Вывод результата


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
