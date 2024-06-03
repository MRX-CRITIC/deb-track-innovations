<?php

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

/** @var yii\web\View $this */
/** @var $card */
/* @var $model app\models\OperationsForm */
/* @var $card_id app\controllers\ProductController */
/* @var $fin_balance app\controllers\ProductController */

$this->title = 'Добавление операции';
$this->params['breadcrumbs'][] = $this->title;

$radioOptions = ($card->lastBalance->fin_balance == 0) ? [true => 'Внесение'] :
    (
    ($card->lastBalance->fin_balance == $card->credit_limit) ? [false => 'Снятие'] :
        [false => 'Снятие', true => 'Внесение']
    );

\app\assets\ProductAsset::register($this);
\app\assets\IndexAsset::register($this);
$formattedBalance = Yii::$app->formatter->asDecimal($card->lastBalance->fin_balance, 2);
?>

<div class="site-product">
    <h1 style="margin-bottom: 1em;"><?= Html::encode($this->title) ?></h1>

    <div style="border-bottom: 1px solid white;">
        <h6>
            <img src="<?= Yii::getAlias('@web') ?> /img/bank-card.png"
                 style="width: 3vh" alt="">
            <?= Html::encode(htmlspecialchars($card->name_card)) ?>:
            <?= Html::encode(htmlspecialchars($formattedBalance)) ?> ₽</h6>
    </div>

    <?php $form = ActiveForm::begin([
        'id' => 'add-operation-form']); ?>

    <div class="date_operation">
        <?= $form->field($model, 'date_operation')->input(
            'date', [
                'value' => date('Y-m-d')
            ]
        ) ?>
    </div>

    <div class="type_operation">
        <?= $form->field($model, 'type_operation')->radioList(
            $radioOptions,
            [
                'id' => 'type_operation',
                'item' => function ($index, $label, $name, $checked, $value) {
                    $id = 'option' . $index;
                    $checkedAttr = $checked ? 'checked' : '';
                    // Используем классы btn-outline-* для неактивных и btn-* для активных
                    $btnClass =
                        $checked ? ($value ? 'btn-success' : 'btn-danger') : ($value ? 'btn-outline-success' : 'btn-outline-danger');
                    return "<input type='radio' class='btn-check' name='{$name}' id='{$id}' value='{$value}' {$checkedAttr} autocomplete='off'>" .
                        "<label class='btn {$btnClass}' for='{$id}'>{$label}</label>";
                }
            ]
        ) ?>
    </div>


    <?= $form->field($model, 'sum')->textInput([
        'type' => 'number',
        'placeholder' => '100000,00',
        'id' => 'sum',
        'pattern' => '[0-9,]*',
        'inputmode' => 'decimal',
    ]) ?>

    <div class="btn-group btn-group-sm" role="group" aria-label="Small button group" style="margin-bottom: 3rem;">
        <?php if ($card->credit_limit <= $card->withdrawal_limit): ?>
            <button type="button" class="btn btn-outline-primary useSum">
                <?= $card->credit_limit ?>
            </button>
        <?php else: ?>
            <button type="button" class="btn btn-outline-primary useSum">
                <?= $card->withdrawal_limit ?>
            </button>
            <button type="button" class="btn btn-outline-primary useSum">
                <?= $card->credit_limit ?>
            </button>
            <button type="button" class="btn btn-outline-primary useSum">
                <?= $card->credit_limit - $card->withdrawal_limit ?>.00
            </button>
        <?php endif; ?>
    </div>

    <?= $form->field($model, 'note')->textarea(['rows' => 2]) ?>

    <div class="form-group">
        <?= Html::submitButton('Добавить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

