<?php

/** @var $model */

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

$form = ActiveForm::begin(); ?>

<?= $form->field($model, 'email')->textInput() ?>
<?= $form->field($model, 'password')->passwordInput() ?>
<?= $form->field($model, 'repeatPassword')->passwordInput() ?>


<?= Html::submitButton('Зарегистрироваться') ?>

<?php ActiveForm::end(); ?>