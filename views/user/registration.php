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
    'placeholder' => 'example@example.com'
]) ?>
<div class="error-message" style="color:red; display:none;" id="error-email"></div>


<?= $form->field($model, 'password')->passwordInput(['id' => 'password']) ?>
<div class="error-message" style="color:red; display:none;" id="error-password"></div>


<?= $form->field($model, 'repeatPassword')->passwordInput() ?>
<div class="error-message" style="color:red; display:none;" id="error-repeatPassword"></div>


<?= Html::submitButton('Зарегистрироваться', ['id' => 'buttonReg']) ?>

<?php ActiveForm::end(); ?>


<div id="overlay-modal" style="display:none;">
    <div id="code-modal">
        <label for="verification-code">
            <input type="text" id="verification-code" maxlength="4" placeholder="••••"/><br>
            <div><p id="error-message-code" style="color:red; display:none;"></p></div>
            <button id="verify-code-btn">Подтвердить код</button>
        </label>

    </div>
</div>

