<?php

namespace app\commands;

use Yii;
use yii\base\Exception;

class RbacController extends \yii\console\Controller
{
    /**
     * @throws Exception
     * @throws \Exception
     */
    public function actionInit() {
        $auth = Yii::$app->authManager;

        $showAbout = $auth->createPermission('showAbout');
        $showAbout->description = 'Дает доступ к about';
        $auth->add($showAbout);

        $showContact = $auth->createPermission('showContact');
        $showContact->description = 'Дает доступ к contact';
        $auth->add($showContact);

        $user = $auth->createRole('user');
        $auth->add($user);
        $auth->addChild($user, $showAbout);

        $admin = $auth->createRole('admin');
        $auth->add($admin);
        $auth->addChild($admin, $showContact);
        $auth->addChild($admin, $user);

        $auth->assign($user, 2);
        $auth->assign($admin, 1);
    }
}