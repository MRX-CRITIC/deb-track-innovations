<?php

namespace app\controllers;

use app\entity\Users;
use app\models\ConfirmationForm;
use app\models\RegistrationForm;
use app\repository\UserRepository;
use Exception;
use yii\web\Response;
use Yii;
use yii\web\Controller;

class UserController extends Controller
{
    /**
     * @throws Exception
     */
    public function actionRegistration()
    {

        $model = new RegistrationForm();
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            if ($model->validate()) {
                $confirmationCode = random_int(1000, 9999);
                Yii::$app->session->set('confirmationCode', $confirmationCode);
                $this->SendConfirmationEmail($model->email, $confirmationCode);

                return ['validation' => true];
            } else {
                return [
                    'validation' => false,
                    'errors' => $model->getErrors(),
                ];
            }
        }
        return $this->render('registration', [
            'model' => $model
        ]);
    }

    public function actionConfirmRegistration()
    {
        $model = new ConfirmationForm();

        $postData = Yii::$app->request->post();
        $wrappedData = ['ConfirmationForm' => $postData];
        Yii::$app->response->format = Response::FORMAT_JSON;
        if (Yii::$app->request->isAjax && $model->load($wrappedData)) {

            if ($model->validate()) {
                if (Yii::$app->session->get('confirmationCode') == $model->confirmationCode) {

                    $userId = UserRepository::createUser(
                        $model->email,
                        $model->password,
                    );
                    Yii::$app->user->login(Users::findIdentity($userId), 0);
                    return ['confirmationCode' => true];
                } else {
                    return [
                        'confirmationCode' => false,
                        'errors' => 'Неверный код подтверждения',
                    ];
                }
            } else {
                return [
                    'confirmationCode' => false,
                    'errors' => 'Не пройдена валидация',
                ];
            }
        } else {
            return  ['errors' => 'Ошибка'];
        }
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
