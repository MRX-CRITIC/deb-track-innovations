<?php

/** @var yii\web\View $this */

/** @var $cards */

use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
use yii\helpers\Url;


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

                <div class="product-info">
                    <div class="header">
                        <?php if (!empty($card->name_card)) {
                            echo '<div> Название карты: ' . '<h6>' . htmlspecialchars($card->name_card) . '</h6>' . ' </div>';
                        } else{
                            echo '<div></div>';
                        } ?>
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
                    <div>Ближайший платеж: /** Дата и сумма **/</div>
                    <div>Возможность снятия/перевода: /****/</div>
                    <br>

                    <div>Банк: <?= Html::encode($card->bank->name) ?> </div>
                    <div>Кредитный лимит: <?= Html::encode($formattedCreditLimit) ?></div>
                    <div>Стоимость обслуживания: <?= Html::encode($formattedCostBanking) ?></div>
                    <div>Льготный период: <?= Html::encode($card->interest_free_period) ?> дней</div>
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