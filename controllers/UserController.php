<?php

namespace app\controllers;

use app\entity\Users;
use app\models\ConfirmationForm;
use app\models\RegistrationForm;
use app\repository\UserRepository;
use yii\web\Response;
use Yii;
use yii\web\Controller;

class UserController extends Controller
{
    public function actionRegistration()
    {

        $model = new RegistrationForm();
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            if ($model->validate()) {
                $confirmationCode = rand(1000, 9999);
                Yii::$app->session->set('confirmationCode', $confirmationCode);
                $this->SendConfirmationEmail($model->email, $confirmationCode);

//                $userId = UserRepository::createUser(
//                    $model->email,
//                    $model->password,
//                );
//
//                Yii::$app->user->login(Users::findIdentity($userId), 0);
//                return $this->redirect('confirm-registration');
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
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;

//            $code = Yii::$app->request->post('code');

            if ($model->validate()) {
                if (Yii::$app->session->get('confirmationCode') == $model->confirmationCode) {
                    $userId = UserRepository::createUser(
                        $model->email,
                        $model->password,
                    );
                    Yii::$app->user->login(Users::findIdentity($userId), 0);
                    return ['codeValid' => true];
//                    return $this->goHome();
                }
            } else {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return [
                    'codeValid' => false,
                    'errors' => $model->getErrors(),
                ];
            }
//            Yii::$app->session->setFlash('error', 'Неверный код подтверждения.');
        }
        Yii::$app->response->format = Response::FORMAT_JSON;
        return  ['errors' => $model->getErrors()];
//        return $this->goHome();
//        return $this->render('registration', [
//            'model' => $model
//        ]);
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
        $email = 'vlad.02.10.69@gmail.com';
        $confirmationCode = rand(1000, 9999);
//        var_dump([Yii::$app->params['supportEmail']]);

        if (!empty($email)) {
            $this->sendConfirmationEmail($email, $confirmationCode);
        } else {
            echo('Неверный email');
        }

    }
}
