<?php

namespace app\repository;
use app\entity\Users;

class UserRepository
{
    public static function getUserBuId($id)
    {
        return Users::find()
            ->where(['id' => $id])
            ->one();
    }

    public static function getUserByLogin($email)
    {
        return Users::find()
            ->where(['email' => $email])
            ->one();
    }

    public static function createUser($email, $password, $confirmationCode)
    {
        $user = new Users();
        $user->email = $email;
        $user->password = password_hash($password, PASSWORD_DEFAULT);
        $user->confirmation_code = $confirmationCode;

        $user->save();
        return $user->id;
    }

}