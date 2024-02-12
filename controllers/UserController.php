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

//    public function actionForm(){
//        $model = new MyForm();
//        if ($model->load(Yii::$app->request->post()) && $model->validate()){
////            var_dump($model);
////            var_dump($model->firstName);
//            exit();
//        }
//        return $this->render('form', [
//            'model' => $model,
//        ]);
//    }

    public function actionRegistration()
    {
        $model = new RegistrationForm();
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($model->validate()) {
                $confirmationCode = rand(1000, 9999);
                Yii::$app->session->set('confirmationCode', $confirmationCode);
                $this->SendConfirmationEmail($model->email, $confirmationCode);

                $userId = UserRepository::createUser(
                    $model->email,
                    $model->password,
                );

                Yii::$app->user->login(Users::findIdentity($userId), 0);
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
        var_dump(Yii::$app->session->get('confirmationCode'));
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if (Yii::$app->session->get('confirmationCode') == $model->confirmationCode) {
                UserRepository::updateStatusUser(Yii::$app->user->id);
                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Неверный код подтверждения.');
            }
        }

        return $this->renderAjax('confirm-registration', [
            'model' => $model
        ]);
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
