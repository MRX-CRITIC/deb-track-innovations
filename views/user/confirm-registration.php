<?php

/** @var $model */

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

$form = ActiveForm::begin(); ?>

<?= $form->field($model, 'confirmationCode')->textInput() ?>
<?= Html::submitButton('Подтвердить') ?>

<?php ActiveForm::end(); ?>