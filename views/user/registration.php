<?php

/** @var $model */

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

\app\assets\ConfirmRegistrationAsset::register($this); // подключение js / css

$form = ActiveForm::begin([
    'id' => 'registration-form',
]);
?>

<?= $form->field($model, 'email')->textInput() ?>
<?= $form->field($model, 'password')->passwordInput() ?>
<?= $form->field($model, 'repeatPassword')->passwordInput() ?>


<?= Html::submitButton('Зарегистрироваться', ['id' => 'buttonReg']) ?>

<?php ActiveForm::end(); ?>

<div id="overlay-modal" style="display:none;">
    <div id="code-modal">
        <label for="verification-code">
            <input type="text" id="verification-code" maxlength="4"/><br>
            <button id="verify-code-btn">Подтвердить код</button>
        </label>
    </div>
</div>

