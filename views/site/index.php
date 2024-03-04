<?php

/** @var yii\web\View $this */

/** @var $cards */

use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
use yii\helpers\Url;

$formatter = \Yii::$app->formatter;

$this->title = 'DebTrack Innovations';
\app\assets\ProductAsset::register($this);
\app\assets\IndexAsset::register($this);
//var_dump(Yii::$app->request->post());
?>

<div class="site-product">
    <a class="add-card-href" href="/product/add-card">Добавить банковский продукт</a>

    <div class="body-content">

        <div class="row">
            <?php foreach ($cards as $card): ?>
                <?php $formattedCreditLimit = Yii::$app->formatter->asDecimal($card->credit_limit, 2); ?>
                <?php $formattedCostBanking = Yii::$app->formatter->asDecimal($card->cost_banking_services, 2); ?>
                <?php $formattedFinBalance = Yii::$app->formatter->asDecimal($card->lastBalance->fin_balance, 2); ?>
                <?php $TotalDebt = Yii::$app->formatter->asDecimal($card->credit_limit - $card->lastBalance->fin_balance, 2); ?>
                <?php $formattedDebt = Yii::$app->formatter->asDecimal(-$card->debt, 2); ?>
                <?php $formattedDatePayment = $formatter->asDate($card->date_payment, 'php:d.m.Y'); ?>
                <?php $formattedDateStart = $formatter->asDate($card->start_date, 'php:d.m.Y'); ?>
                <?php $formattedDateEnd = $formatter->asDate($card->end_date, 'php:d.m.Y'); ?>

                <div class="product-info">
                    <div class="header">

                        <div>Название карты: <h6><?= Html::encode(htmlspecialchars($card->name_card)) ?></h6></div>
                        <div class="links">
                            <a class="add-operation" href="<?=
                            Yii::$app->urlManager->createUrl([
                                '/product/add-operation',
                                'card_id' => $card->id,
                            ]) ?>" data-method="post">Добавить операцию</a>
                        </div>
                    </div>

                    <br>
                    <div>Баланс: <?= Html::encode($formattedFinBalance) ?></div>
                    <br>
                    <?php if (!empty($formattedDebt > 0 && $formattedDatePayment)): ?>
                        <div>Ближайший платеж:
                            <span style="color: red; font-weight: bold;">
                                <?= Html::encode($formattedDebt) ?>
                            </span>
                            оплатить до
                            <span style="text-decoration: underline;">
                                <?= Html::encode($formattedDatePayment) ?>
                            </span>
                        </div>
                        <div style="font-size: 0.95rem; margin: 5px 0 5px 0;">Сумма ближайшего платежа ровна сумме
                            операций за расчетный период:
                            <span style="text-decoration: underline;">
                                <?= Html::encode($formattedDateStart . ' - ' . $formattedDateEnd) ?>
                            </span>
                        </div>
                        <div>Общая задолженность: <?= Html::encode(htmlspecialchars($TotalDebt)) ?></div>
                    <?php else: ?>
                        <div>Ближайший платеж:
                            <span style="color: #00FF00">задолженность отсутствует</span>
                        </div>
                    <?php endif; ?>


                    <div>Возможность снятия/перевода:
                        <?php if ($card->credit_limit <= $card->withdrawal_limit): ?>
                            <?= Html::encode($formattedCreditLimit) ?>
                        <?php else: ?>
                            <?= Html::encode($card->withdrawal_limit) ?>
                        <?php endif; ?>
                    </div>
                    <br>

                    <div>Банк: <?= Html::encode($card->bank->name) ?> </div>
                    <div>Кредитный лимит: <?= Html::encode($formattedCreditLimit) ?></div>
                    <div>Стоимость обслуживания: <?= Html::encode($formattedCostBanking) ?></div>
                    <div>Льготный период: <?= Html::encode($card->grace_period) ?> дней</div>
                    <div><?php if (!empty($card->note)): ?>
                            <a class="a-note"
                               data-bs-toggle="collapse"
                               href="#collapseNote-<?= $card->id ?>"
                               aria-expanded="false"
                               aria-controls="collapseNote-<?= $card->id ?>">
                                Показать примечание
                            </a>
                            <div class="collapse" id="collapseNote-<?= $card->id ?>">
                                <div class="card-note">
                                    <?= Html::encode($card->note) ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                </div>
            <?php endforeach; ?>
        </div>

    </div>
</div>