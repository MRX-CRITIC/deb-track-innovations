<?php

/** @var $model */

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;
\app\assets\ConfirmRegistrationAsset::register($this);

$form = ActiveForm::begin([
    'id' => 'registration-form',
]); ?>


<?= $form->field($model, 'email')->textInput([
    'id' => 'email',
    'class' => 'input-field',
    'placeholder' => 'example@example.com',
    'autofocus' => true,
])->label('Электронная почта') ?>
<div class="error-message" style="color:red; display:none;" id="error-email"></div>


<?= $form->field($model, 'password')->passwordInput(['id' => 'password', 'class' => 'input-field', 'minLength' => 8])->label('Пароль') ?>
<div class="error-message" style="color:red; display:none;" id="error-password"></div>


<?= $form->field($model, 'repeatPassword')->passwordInput(['id' => 'repeatPassword', 'class' => 'input-field', 'minLength' => 8])->label('Повторите пароль') ?>
<div class="error-message" style="color:red; display:none;" id="error-repeatPassword"></div>


<?= Html::submitButton('Зарегистрироваться', ['id' => 'registration-btn']) ?>

<?php ActiveForm::end(); ?>


<div id="overlay-modal" style="display: none;">
    <div id="code-modal">
        <label for="verification-code"><p id="title-code">Введите код подтверждения</p>
            <p id="info">Письмо с кодом отправлено на Ваш E-mail</p>
            <div id="code-inputs">

            <?php for ($i = 1; $i <= 4; $i++): ?>
            <?= $form->field($model, 'verificationCode[]')->input('text',[
                'class' => 'code-input',
                'id' => 'verification-code-' . $i,
                'placeholder' => '•',
                'maxlength' => 1,
                'autocomplete' => 'off',
                'data-index' => $i,
            ])->label(false)?>
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