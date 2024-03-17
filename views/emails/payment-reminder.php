<?php
/** @var $payment */

$formattedDate = Yii::$app->formatter->asDate($payment['date_payment'], 'dd.MM.yyyy');
$formattedDateStart = Yii::$app->formatter->asDate($payment['start_date'], 'dd.MM.yyyy');
$formattedDateEnd = Yii::$app->formatter->asDate($payment['end_date'], 'dd.MM.yyyy');

$formattedPaymentInt = -floatval($payment['debt']);
$formattedPayment = number_format($formattedPaymentInt, 2, ',', ' ');
?>
<div style="max-width: 600px; margin: auto; background-color: #f7f7f7; padding: 20px; border-radius: 2em; font-family: Arial, sans-serif;">
    <h1 style="font-size: 1.5em; text-align: center; font-weight: bold;"> Информация по карте <?= $payment['name_card'] ?></h1>
    <p style="text-align: center;"> За период<br> <?= $formattedDateStart . ' - ' . $formattedDateEnd ?></p>
    <div style="margin-top: 20px; border-top: 1px solid #ccc; padding-top: 10px; font-size: 1.05em">
        <p style="text-align: left;">
            <strong>Оплатить до <?= $formattedDate ?>:</strong>
            <strong style="float: right;"><?= $formattedPayment ?> руб.</strong>
        </p>
    </div>
</div>
