<?php

namespace app\services\user;

use Yii;

class RegistrationServices
{
    public static function SendConfirmationEmail($email, $confirmationCode)
    {
        Yii::$app->mailer->compose('/emails/confirm-email', ['confirmationCode' => $confirmationCode])
            ->setTo($email)
            ->setFrom("info@deb-track-innovations.ru")
            ->setSubject('Подтверждение адреса электронной почты')
            ->send();
    }
}