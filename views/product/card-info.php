<?php
/* @var $card */

use yii\helpers\Html;

$this->title = 'Информация по карте';
$this->params['breadcrumbs'][] = $this->title;

\app\assets\ProductAsset::register($this);
\app\assets\IndexAsset::register($this);

$formatter = \Yii::$app->formatter;
$formattedFinBalance = Yii::$app->formatter->asDecimal($card->lastBalance->fin_balance, 2);
$formattedCreditLimit = Yii::$app->formatter->asDecimal($card->credit_limit, 2);
$formattedCostBanking = Yii::$app->formatter->asDecimal($card->cost_banking_services, 2);
$TotalDebt = Yii::$app->formatter->asDecimal($card->credit_limit - $card->lastBalance->fin_balance, 2);
$formattedDebt = Yii::$app->formatter->asDecimal(-$card->debt, 2);
$formattedWithdrawalLimit = Yii::$app->formatter->asDecimal($card->actual_withdrawal_limit, 2);
$formattedDatePayment = $formatter->asDate($card->date_payment, 'php:d.m.Y');
$formattedDateStart = $formatter->asDate($card->start_date, 'php:d.m.Y');
$formattedDateEnd = $formatter->asDate($card->end_date, 'php:d.m.Y');
?>

<div>
    <div class="top">
        <p style="font-size: 1.5em; padding-top: 2vh;"><?= Html::encode($card->name_card) ?></p>
        <div style="font-size: 1.25em; margin-bottom: 1vh;"><?= Html::encode($formattedFinBalance) ?>₽</div>
    </div>

    <div style="padding: 2vh 0 1vh 3vh;">
        <div>Банк: <span>
                <?= Html::encode($card->bank->name) ?>
            </span>
        </div>
        <div>Кредитный лимит: <span>
                <?= Html::encode($formattedCreditLimit) ?>
            </span>
        </div>
        <div>Льготный период: <span>
                <?= Html::encode($card->grace_period) ?> дней
            </span>
        </div>
        <div>Стоимость обслуживания: <span>
                <?= Html::encode($formattedCostBanking) ?>
            </span>
        </div>
        <div>Общая задолженность: <span>
                <?= Html::encode($TotalDebt) ?>
            </span>
        </div>
        <br>

        <?php if (!empty($formattedDebt > 0 && $formattedDatePayment)): ?>
            <div>Расчетный период: <br> <span>
                    <?= Html::encode(Html::encode($formattedDateStart . ' - ' . $formattedDateEnd)) ?>
                </span>
            </div>
        <?php endif; ?>

        <?php if ($card->percentage_partial_repayment != null): ?>
                <div>Частичное погашение: <span>
                        <?= Html::encode($card->percentage_partial_repayment * 100) . '%' ?>
                    </span>
                </div>
        <?php endif; ?>

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

    <div>
        <a href="#" class="add-card-href" style="float: right; margin-bottom: 3vh; margin-right: 3vh; width: 8em;">
            Редактировать
        </a>

        <a href="#" class="delete-card">
            Удалить
        </a>
    </div>

</div>
