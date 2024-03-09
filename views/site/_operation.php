<?php

/** @var yii\web\View $this */

/** @var $model yii\data\ActiveDataProvider */

use app\services\OperationsServices;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
use yii\widgets\ListView;
use yii\widgets\LinkPager;


$this->title = '';
\app\assets\ProductAsset::register($this);
\app\assets\IndexAsset::register($this);

$currentDate = $model->date_operation;

$formattedSum = Yii::$app->formatter->asDecimal($model->sum, 2);
$formattedCreditLimit = Yii::$app->formatter->asDecimal($model->card->credit_limit, 2);
$formattedDate = Yii::$app->formatter->asDate($currentDate, 'dd.MM.yyyy');
?>

    <?php if (OperationsServices::$lastOperationDate !== $currentDate): ?>
    <?php OperationsServices::$lastOperationDate = $currentDate; ?>
    <tr class="date-header">
        <td colspan="3">
            <?= Html::encode($formattedDate) ?>
        </td>
    </tr>
    <tr>
        <td colspan="3" class="line-date">
        </td>
    </tr>
    <?php endif; ?>

<tr class="operation">
    <td>

        <div class="name-card"><?= Html::encode($model->card->name_card) ?></div>
        <div class="name-bank">Кредитный лимит: <?= Html::encode($formattedCreditLimit) ?></div>
        <div class="name-bank"><?= Html::encode($model->card->bank->name) ?></div>

        <div><?php if (!empty($model->note)): ?>
                <a class="a-note"
                   data-bs-toggle="collapse"
                   href="#collapseNote-<?= $model->id ?>"
                   aria-expanded="false"
                   aria-controls="collapseNote-<?= $model->id ?>">
                    Примечание
                </a>
                <div class="collapse" id="collapseNote-<?= $model->id ?>">
                    <div class="card-note">
                        <?= Html::encode($model->note) ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <br>
        <a class="delete-link" href="<?=
        Yii::$app->urlManager->createUrl([
            'product/delete-operation',
            'id' => $model->id,
            'card_id' => $model->card_id])
        ?>" data-method="post">удалить операцию</a>
    </td>

    <td class="sum">
        <?php
        $sign = $model->type_operation == 1 ? "+" : "-";
        $color = $model->type_operation == 1 ? "color: #00FF00;" : "";
        echo "<span style='" . $color . "; text-decoration: none;'>" . $sign . Html::encode($formattedSum) . "</span>"; ?>
    </td>

</tr>





