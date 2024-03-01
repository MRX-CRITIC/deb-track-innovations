<?php

/** @var yii\web\View $this */

/** @var $operations */

use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
use yii\widgets\ListView;
use yii\widgets\LinkPager;


$this->title = 'DebTrack Innovations';
\app\assets\ProductAsset::register($this);
\app\assets\IndexAsset::register($this);
$currentDate = null;
?>


<div class="site-product">
    <div class="row">
        <table>
            <tr>
                <td></td>
                <td class="title-table-operation">Сумма операции</td>
                <td></td>
            </tr>

            <?php foreach ($operations as $operation): ?>
                <?php $formattedSum = Yii::$app->formatter->asDecimal($operation->sum, 2); ?>
                <?php $formattedCreditLimit = Yii::$app->formatter->asDecimal($operation->card->credit_limit, 2); ?>
                <?php $formattedDate = Yii::$app->formatter->asDate($operation->date_operation, 'dd.MM.yyyy'); ?>


                <?php if ($currentDate !== $formattedDate): ?>
                    <?php $currentDate = $formattedDate; ?>
                    <tr class="date-header">
                        <td colspan="3"><?= Html::encode($currentDate) ?></td>
                    </tr>
                    <tr>
                        <td colspan="3" class="line-date"></td>
                    </tr>
                <?php endif; ?>

                <tr class="operation">

                    <td>
                        <div class="name-card"><?= Html::encode($operation->card->name_card) ?></div>
                        <div class="name-bank">Кредитный лимит: <?= Html::encode($formattedCreditLimit) ?></div>
                        <div class="name-bank"><?= Html::encode($operation->card->bank->name) ?></div>

                        <div><?php if (!empty($operation->note)): ?>
                                <a class="a-note"
                                   data-bs-toggle="collapse"
                                   href="#collapseNote-<?= $operation->id ?>"
                                   aria-expanded="false"
                                   aria-controls="collapseNote-<?= $operation->id ?>">
                                    Примечание
                                </a>
                                <div class="collapse" id="collapseNote-<?= $operation->id ?>">
                                    <div class="card-note">
                                        <?= Html::encode($operation->note) ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        <br>
                        <a class="delete-link" href="<?=
                        Yii::$app->urlManager->createUrl([
                            'product/delete-operation',
                            'id' => $operation->id,
                            'card_id' => $operation->card_id])
                        ?>" data-method="post">удалить операцию</a>
                    </td>

                    <td class="sum">
                        <?php
                        $sign = $operation->type_operation == 1 ? "+" : "-";
                        $color = $operation->type_operation == 1 ? "color: #00FF00;" : "";
                        echo "<span style='" . $color . "'>" . $sign . Html::encode($formattedSum) . "</span>"; ?>
                    </td>
                </tr>
            <?php endforeach; ?>

        </table>
    </div>
</div>



