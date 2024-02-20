<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */

/** @var app\models\LoginForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

\app\assets\LoginAsset::register($this);

$this->title = 'Авторизация';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="body">
    <div class="brand-logo">
        <div class="img-logo"></div>
        <?= Yii::$app->name; ?>
    </div>
    <div class="line"></div>
    <div class="login-container">
        <div class="login-form">

            <div class="reg-title">
                <h1><?= Html::encode($this->title) ?></h1>
                <p>Пожалуйста, заполните следующие поля для входа: </p>
            </div>

            <?php $form = ActiveForm::begin([
                'id' => 'login-form',
            ]); ?>

            <div class="form-group">
                <?= $form->field($model, 'email')->textInput([
                    'id' => 'email',
                    'class' => 'input-field',
                    'placeholder' => 'example@example.com',
                    'autofocus' => true
                ])->label('Электронная почта', ['class' => 'form-label']) ?>
                <div class="error-message" style="display:none;" id="error-email"></div>
            </div>


            <div class="form-group">
                <?= $form->field($model, 'password')->passwordInput([
                    'id' => 'password',
                    'class' => 'input-field',
                    'placeholder' => '••••••••••••••••••',
                ])->label('Пароль', ['class' => 'form-label']) ?>
                <div class="error-message" style="display:none;" id="error-password"></div>
            </div>

            <?= $form->field($model, 'rememberMe')->checkbox([
                'template' => "<div class=\"custom-control custom-checkbox\">{input} {label}</div>\n<div class=\"col-lg-8\">{error}</div>",
            ]) ?>



            <?= Html::submitButton('Авторизоваться', ['class' => 'login-button', 'name' => 'login-button']) ?>
            <div class="transition">Если у вас нет аккаунта - <a href="registration">зарегистрироваться</a></div>


            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
