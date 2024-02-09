<?php

namespace app\controllers;

use app\entity\Users;
use app\models\ConfirmationForm;
use app\models\MyForm;
use app\models\RegistrationForm;
use app\repository\UserRepository;
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
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $confirmationCode = rand(1000, 9999);
//            $_SESSION['confirmationCode'] = $confirmationCode;
            Yii::$app->session->set('confirmationCode', $confirmationCode);
            $this->SendConfirmationEmail($model->email, $confirmationCode);

            $userId = UserRepository::createUser(
                $model->email,
                $model->password,
            );
            Yii::$app->user->login(Users::findIdentity($userId), 0);
            return $this->redirect('confirm-registration');
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
