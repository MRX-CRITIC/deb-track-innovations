<?php

/** @var $model */

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

\app\assets\ConfirmRegistrationAsset::register($this);
?>
<div class="body">
    <div class="login-container">
        <div class="brand-logo"></div>
        <div class="login-form">

            <?php
            $form = ActiveForm::begin([
                'id' => 'registration-form',
                'class' => '',
            ]); ?>

            <div class="form-group">
                <?= $form->field($model, 'email')->textInput([
                    'id' => 'email',
                    'class' => 'input-field',
//                    'placeholder' => 'example@example.com',
                    'autofocus' => true,
                ])->label('Электронная почта', ['class' => 'form-label']) ?>
                <div class="error-message" style="display:none;" id="error-email"></div>
            </div>

            <div class="form-group">
                <?= $form->field($model, 'password')->passwordInput([
                    'id' => 'password',
                    'class' => 'input-field',
                    'minLength' => 8
                ])->label('Пароль', ['class' => 'form-label']) ?>
                <div class="error-message" style="display:none;" id="error-password"></div>
            </div>

            <div class="form-group">
                <?= $form->field($model, 'repeatPassword')->passwordInput([
                    'id' => 'repeatPassword',
                    'class' => 'input-field',
                    'minLength' => 8
                ])->label('Повторите пароль', ['class' => 'form-label']) ?>
                <div class="error-message" style="display:none;" id="error-repeatPassword"></div>
            </div>

            <?= Html::submitButton('Зарегистрироваться', [
                'id' => 'registration-btn',
                'class' => 'login-button'
            ]) ?>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>


<div id="overlay-modal" style="display: none;">
    <div id="code-modal">
        <label for="verification-code"><p id="title-code">Введите код подтверждения</p>
            <p id="info">Письмо с кодом отправлено на Ваш E-mail</p>
            <div id="code-inputs">

                <?php for ($i = 1; $i <= 4; $i++): ?>
                    <?= $form->field($model, 'verificationCode[]')->input('text', [
                        'class' => 'code-input',
                        'id' => 'verification-code-' . $i,
                        'placeholder' => '•',
                        'maxlength' => 1,
                        'autocomplete' => 'off',
                        'data-index' => $i,
                    ])->label(false) ?>
                <?php endfor; ?>

            </div>
            <div><p id="error-message-code"></p></div>
            <button id="verify-code-btn">Подтвердить код</button>

            <div>
                <button id="resend-code-btn" style="display: none;">
                    Запросить код повторно
                </button>
            </div>

        </label>
    </div>
</div>