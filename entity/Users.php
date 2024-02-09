<?php

namespace app\entity;

use app\repository\UserRepository;

use yii\web\IdentityInterface;
use yii\db\ActiveRecord;

class Users extends \yii\db\ActiveRecord implements IdentityInterface
{
    public static function findIdentity($id)
    {
        return UserRepository::getUserBuId($id);
    }

    public function getId()
    {
        return $this->id;
    }

    public function validatePassword($password) {
        return password_verify($password, $this->password);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
    }
    public function getAuthKey()
    {
    }
    public function validateAuthKey($authKey)
    {
    }
}