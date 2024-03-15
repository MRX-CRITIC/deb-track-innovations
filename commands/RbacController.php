<?php

namespace app\commands;

use Yii;
use yii\base\Exception;
use yii\console\Controller;

class RbacController extends Controller
{
    /**
     * @throws \Exception
     */
    public function actionInit() {
        $auth = Yii::$app->authManager;

        $showTest = $auth->createPermission('showTest');
        $showTest->description = 'Дает доступ к экшену для тестов';
        $auth->add($showTest);

//        $showContact = $auth->createPermission('showContact');
//        $showContact->description = 'Дает доступ к contact';
//        $auth->add($showContact);

        $user = $auth->createRole('user');
        $auth->add($user);
//        $auth->addChild($user, $showAbout);

        $admin = $auth->createRole('admin');
        $auth->add($admin);
        $auth->addChild($admin, $showTest);
//        $auth->addChild($admin, $user);

//        $auth->assign($user, 2);
        $auth->assign($admin, 0);
    }

    /**
     * @throws \Exception
     */
    public function actionAddAdmin() {
        $auth = Yii::$app->authManager;

        $adminRole = $auth->getRole('admin');

        $userIds = [4];

        foreach ($userIds as $userId) {
            $auth->assign($adminRole, $userId);
        }
    }
}