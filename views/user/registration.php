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
    'placeholder' => 'example@example.com',
//    'autofocus' => true,
])->label('Электронная почта') ?>
<div class="error-message" style="color:red; display:none;" id="error-email"></div>


<?= $form->field($model, 'password')->passwordInput(['id' => 'password', 'minLength' => 8])->label('Пароль') ?>
<div class="error-message" style="color:red; display:none;" id="error-password"></div>


<?= $form->field($model, 'repeatPassword')->passwordInput(['id' => 'repeatPassword', 'minLength' => 8])->label('Повторите пароль') ?>
<div class="error-message" style="color:red; display:none;" id="error-repeatPassword"></div>


<?= Html::submitButton('Зарегистрироваться', ['id' => 'buttonReg']) ?>

<?php ActiveForm::end(); ?>


<div id="overlay-modal" style="display:none;">
    <div id="code-modal">
        <label for="verification-code">
            <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />
            <?= $form->field($model, 'verificationCode')->input('text',[
                'id' => 'verification-code',
                'placeholder' => '••••',
                'maxlength' => 4,
                'autofocus' => true,
            ])-> label('Введите код подтверждения')?>
<!--            <input type="number" id="verification-code" maxlength="4" placeholder="••••"/><br>-->
            <div><p id="error-message-code" style="color:red; display:none;"></p></div>
            <button id="verify-code-btn">Подтвердить код</button>
        </label>
    </div>
</div>

