<?php

/** @var yii\web\View $this */

/** @var $cardsUpdate */

use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
use yii\helpers\Url;

$formatter = \Yii::$app->formatter;

$this->title = 'DebTrack Innovations';
\app\assets\ProductAsset::register($this);
\app\assets\IndexAsset::register($this);
?>

<div class="control-panel">
    <a class="add-card-href" href="/product/add-card">Добавить карту</a>
</div>

<div class="body-content">

    <div class="content-row">

        <?php foreach ($cardsUpdate as $card): ?>

            <?php $formattedFinBalance = Yii::$app->formatter->asDecimal($card->lastBalance->fin_balance, 2); ?>
            <?php $formattedDebt = Yii::$app->formatter->asDecimal(-$card->debt, 2); ?>
            <?php $formattedDatePayment = $formatter->asDate($card->date_payment, 'php:d.m.Y'); ?>
            <?php $formattedWithdrawalLimit = Yii::$app->formatter->asDecimal($card->actual_withdrawal_limit, 2); ?>

            <div class="product-info">
                <div class="header">

                    <div>
                        Название карты:
                        <h5 style="
                            display: flex;
                            flex-direction: row;
                            align-items: center;
                            gap: 0.5vh;
                            flex-wrap: wrap;
                            ">
                            <?= Html::encode(htmlspecialchars($card->name_card)) ?>
                            <span style="text-align: -webkit-center;">
                                    <a class="card-info add-operation" id="card-info" href="<?=
                                    Yii::$app->urlManager->createUrl([
                                        '/product/card-info',
                                        'card_id' => $card->id,
                                    ]) ?>"
                                       data-method="post"
                                       title="Информация о карте">
                                        <img src="<?= Yii::getAlias('@web') ?> /img/info.png"
                                             style="width: 3vh" alt="Информация">
                                    </a>
                            </span>
                        </h5>
                    </div>
                    <div class="links">
                        <a class="add-operation" href="<?=
                        Yii::$app->urlManager->createUrl([
                            '/product/add-operation',
                            'card_id' => $card->id,
                        ]) ?>"
                           data-method="post"
                           title="Добавить операцию">
                            <img src="<?= Yii::getAlias('@web') ?> /img/add-operation.png"
                                 style="width: 5vh" alt="Добавить операцию">
                        </a>
                    </div>
                </div>


                <div style="margin: 3vh 0 0 0;">Баланс: <?= Html::encode($formattedFinBalance) ?>₽</div>

                <div>Возможность снятия/перевода:
                    <?= Html::encode($formattedWithdrawalLimit) ?>₽
                </div><br>

                <?php if ($formattedDebt > 0 && !empty($formattedDatePayment)): ?>

                    <div>Ближайший платеж:
                        <span style="color: red; font-weight: bold;">
                                <?= Html::encode($formattedDebt) ?>₽
                        </span>

                        <span class="term">оплатить до
                            <span style="text-decoration: underline;">
                                <?= Html::encode($formattedDatePayment) ?>
                            </span>
                        </span>
                        <?php $uniqueKey = 'warning_' . $card->id;
                        $flashes = Yii::$app->session->getAllFlashes();
                        if (isset($flashes[$uniqueKey])) {
                            $message = $flashes[$uniqueKey];
                            echo "<div class='alert alert-warning d-flex align-items-center'>{$message}</div>";
                        } ?>
                    </div>

                <?php else: ?>

                    <div>Ближайший платеж:
                        <span style="color: #00FF00">не найден</span>
                    </div>

                <?php endif; ?>

            </div>
        <?php endforeach; ?>
    </div>
</div>