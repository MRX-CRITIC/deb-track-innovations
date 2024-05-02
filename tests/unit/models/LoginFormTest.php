<?php

namespace unit\models;

use app\entity\Users;
use app\models\LoginForm;
use app\repository\UserRepository;
use Cassandra\Date;

require_once 'vendor/autoload.php';

class LoginFormTest extends \Codeception\Test\Unit
{
    // тест на валидацию корректных данных
    /**
     * @throws \Exception
     */
    public function testValidateCorrectData()
    {
        $user = new Users();
        $user->email = 'mrx.critic@gmail.com';
        $user->password = 'mrx.critic@gmail.com';
//        $user->last_login = new \DateTime(time());

        // создания мок репозитория пользователя
        $userRepository = $this->getMockBuilder(UserRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        // настройка мока для получения пользователя по email
        $userRepository->expects($this->any())
            ->method('getUserByLogin')
            ->with('mrx.critic@gmail.com')
            ->willReturn($user);

        $model = new LoginForm();
        $model->email = 'mrx.critic@gmail.com';
        $model->password = 'mrx.critic@gmail.com';
//        $model->last_login = new \DateTime(time());

        // проверка на успешную валидацию
        $this->assertTrue($model->validate());

        if (!$model->validate()) {
            print_r($model->getErrors());
        }

        // прверка на наличие ошибок
        $this->assertEquals(null, $model->errors);
    }
}
