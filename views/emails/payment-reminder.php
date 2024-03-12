<?php
/* @var $payment integer */

$formattedDate = Yii::$app->formatter->asDate($payment['date_payment'], 'dd.MM.yyyy');
$formattedPayment = Yii::$app->formatter->asDecimal(-$payment['debt'], 2);
?>

<p> Внесите платеж по карте: <h4> <?= $payment['name_card'] ?> </h4> на сумму <?= $formattedPayment ?> до <?= $formattedDate ?> </p>

<div>
    <p>
<!--        <strong>--><?//= $payment ?><!--</strong>-->
    </p>
</div>
