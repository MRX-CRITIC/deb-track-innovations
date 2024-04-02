<?php

namespace app\controllers;

use app\commands\AlertController;
use app\entity\Payments;
use app\models\OperationSearchForm;
use app\services\product\CardsServices;
use app\services\site\IndexServices;
use app\services\product\PaymentsServices;
use Exception;
use app\entity\Cards;
use app\repository\BalanceRepository;
use app\repository\BanksRepository;
use app\repository\CardsRepository;
use app\repository\OperationsRepository;
use app\repository\PaymentsRepository;
use app\services\product\OperationsServices;
use DateInterval;
use DateTime;
use Yii;
use yii\base\InvalidConfigException;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\HttpException;
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
                        'actions' => ['index', 'account', 'operations', 'test'],
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
            'pageCache' => [
                'class' => 'yii\filters\PageCache',
                'only' => ['index', 'about'],
                'duration' => 10,
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

        IndexServices::paymentReminder($user_id);
        $cards = CardsRepository::getAllCardsWithDebtsAndPayments($user_id);
        $cardsUpdate = CardsServices::actualWithdrawalLimit($cards);

        $allTotalDebt = IndexServices::AllTotalDebt($cards);

        return $this->render('index', [
            'cardsUpdate' => $cardsUpdate,
            'allTotalDebt' => $allTotalDebt,
        ]);
    }

    /**
     * @throws HttpException
     */
    public function actionTest()
    {
        if (\Yii::$app->user->can('showTest')) {
            $user_id = Yii::$app->user->getId();


        } else {
            throw new HttpException(404, 'У вас нет доступа к этой странице');
        }
    }

    public function actionOperations()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect('/site/about');
        }
        $user_id = Yii::$app->user->getId();
        $model = new OperationSearchForm();

        if ($model->load(Yii::$app->request->get()) && $model->validate()) {
            $selectedCardName = $model->name_card;
        } else {
            $selectedCardName = null;
        }
        $cards = CardsRepository::getUniqueCardNamesByUserId($user_id);
        $cardsList = ArrayHelper::map($cards, 'name_card', 'name_card');
        $operationsDataProvider = OperationsRepository::getAllOperations($user_id, $selectedCardName, $model->date_operation);

        return $this->render('operations', [
            'model' => $model,
            'operationsDataProvider' => $operationsDataProvider,
            'cardsList' => $cardsList,
            'selectedCardName' => $selectedCardName,
        ]);
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionAccount()
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('account', [
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

//    public function actionError()
//    {
//        $exception = Yii::$app->errorHandler->exception;
//        if ($exception !== null) {
//            return $this->render('error', [
//                'exception' => $exception
//            ]);
//        }
//    }
}
