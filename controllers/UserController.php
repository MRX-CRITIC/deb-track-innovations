<?php

namespace app\controllers;

use app\entity\Users;
use app\models\ConfirmationForm;
use app\models\RegistrationForm;
use app\repository\UserRepository;
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
                'only' => ['registration'],
                'rules' => [
                    [
                        'actions' => ['registration'],
                        'allow' => true,
                        'roles' => ['?'],
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
        $model = new RegistrationForm();
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            if ($model->validate()) {

                if (empty(Yii::$app->session->get('time')) || (Yii::$app->session->get('time') + 60) < time()) {
                    $confirmationCode = random_int(1000, 9999);
                    Yii::$app->session->set('confirmationCode', $confirmationCode);
                    Yii::$app->session->set('model', $model);
                    Yii::$app->session->set('time', time());
                    $this->SendConfirmationEmail($model->email, $confirmationCode);
                    return [
                        'validation' => true,
                        'time' => true,
                    ];
                }
                return [
                    'validation' => false,
                    'time' => false,
                    'errors' => 'Используете код который был направлен ранее или дождитесь таймера',
                ];

            } else {
                return [
                    'validation' => false,
                    'errors' =>  'Не пройдена валидация',
                ];
            }
        }
        return $this->render('registration', [
            'model' => $model
        ]);
    }

    /**
     * @throws HttpException
     */
    public function actionConfirmRegistration()
    {
        $model = new ConfirmationForm();
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post(), '')) {

            if ($model->validate()) {
                if (Yii::$app->session->get('model')->email == $model->email &&
                    Yii::$app->session->get('model')->password == $model->password) {

                    if (Yii::$app->session->get('confirmationCode') == $model->confirmationCode) {

                        $userId = UserRepository::createUser(
                            $model->email,
                            $model->password,
                            $model->confirmationCode,
                        );
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
        Yii::$app->response->format = Response::FORMAT_HTML;
        throw new HttpException(404, 'Страница не найдена');
    }

    public function SendConfirmationEmail($email, $confirmationCode)
    {
        Yii::$app->mailer->compose('/emails/confirm-email', ['confirmationCode' => $confirmationCode])
            ->setTo($email)
            ->setFrom("money.back.monitoring@gmail.com")
            ->setSubject('Подтверждение адреса электронной почты')
            ->send();
    }

    public function actionTest()
    {
    }

    public function actionIndex()
    {
    }
}
