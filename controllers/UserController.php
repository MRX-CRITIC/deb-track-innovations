<?php

namespace app\controllers;

use app\entity\Users;
use app\models\ConfirmationForm;
use app\models\LoginForm;
use app\models\RegistrationForm;
use app\repository\UserRepository;
use app\services\user\RegistrationServices;
use Exception;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\HttpException;
use yii\web\Response;
use Yii;
use yii\web\Controller;

class UserController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['registration', 'login', 'confirm-registration'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout', 'index'],
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
     * @throws Exception
     */
    public function actionRegistration()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }


        $model = new RegistrationForm();
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            if ($model->validate()) {
                if (empty(Yii::$app->session->get('time')) ||
                    (Yii::$app->session->get('time') + 60) < time() ||
                    $model->email != Yii::$app->session->get('model')->email) {

                    $confirmationCode = random_int(1000, 9999);

                    Yii::$app->session->set('confirmationCode', $confirmationCode);
                    Yii::$app->session->set('model', $model);
                    Yii::$app->session->set('time', time());

                    RegistrationServices::SendConfirmationEmail($model->email, $confirmationCode);
                    return [
                        'validation' => true,
                        'time' => false,
                        'errorsYii' => $model->getErrors(),
                    ];
                }
                return [
                    'validation' => false,
                    'time' => true,
                    'errors' => 'Используете код который был направлен ранее или дождитесь таймера',
                    'errors_Yii' => $model->getErrors(),
                ];

            } else {
                return [
                    'validation' => false,
                    'time' => false,
                    'errors' => 'Не пройдена валидация',
                    'errorsYii' => $model->getErrors(),
                ];
            }
        }
        $this->view->params['homeLink'] = false;
        return $this->render('registration', [
            'model' => $model
        ]);
    }

    /**
     * @throws HttpException
     */
    public function actionConfirmRegistration()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new ConfirmationForm();
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post(), '')) {

            if ($model->validate()) {
                if (Yii::$app->session->get('model')->email === $model->email &&
                    Yii::$app->session->get('model')->password === $model->password) {

                    if (Yii::$app->session->get('confirmationCode') === $model->confirmationCode) {

                        $userId = UserRepository::createUser(
                            $model->email,
                            $model->password,
                            $model->confirmationCode,
                        );
                        Yii::$app->session->removeAll();
                        Yii::$app->user->login(Users::findIdentity($userId), 0);
                        return [
                            'confirmationCode' => true,
                            'validation' => true,
                        ];
                    } else {
                        return [
                            'confirmationCode' => false,
                            'validation' => true,
                            'errors' => 'Неверный код',
                        ];
                    }
                } else {
                    return [
                        'confirmationCode' => false,
                        'validation' => false,
                        'errors' => $model->getErrors(),
                    ];
                }
            } else {
                return [
                    'confirmationCode' => false,
                    'validation' => false,
                    'errors' => $model->getErrors(),
                ];
            }
        }
        throw new HttpException(404, 'Страница не найдена');
    }

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->login()) {

                $user = Yii::$app->user->identity;
                $user->last_login = new \yii\db\Expression('NOW()');
                $user->save(false, ['last_login']);

                if (Yii::$app->request->isAjax) {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return ['validation' => $model->login()];
                }
                return $this->goBack();
            } elseif (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return [
                    'validation' => $model->login(),
                    'errors' => $model->getErrors(),
                ];
            }
        }
        $this->view->params['homeLink'] = false;
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->redirect(['user/login']);
    }
}
