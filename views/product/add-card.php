<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;

/* @var $this yii\web\View */
/* @var $model app\models\CardsForm */
/* @var $banksList app\controllers\ProductController */

$this->title = 'Добавление карты';
$this->params['breadcrumbs'][] = $this->title;
\app\assets\ProductAsset::register($this);
?>
<div class="site-add-card">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin([
        'id' => 'add-card-form',
    ]); ?>

    <?= $form->field($model, 'user_id')->hiddenInput([
            'value'=> Yii::$app->user->identity->id])->label(false) ?>

    <?= $form->field($model, 'bank_id')->dropDownList($banksList, [
        'prompt' => 'Выберите банк',
        'id' => 'bank-select',
    ]) ?>

    <?= $form->field($model, 'name_card')->textInput([
        'placeholder' => 'Не обязательно',
        'id' => 'name-card',
    ]) ?>

    <?= $form->field($model, 'credit_limit')->textInput([
        'type' => 'text',
        'placeholder' => '100 000',
        'id' => 'credit-limit',
    ]) ?>

    <?= $form->field($model, 'cost_banking_services')->textInput([
        'type' => 'number',
        'placeholder' => '990',
        'id' => 'cost-banking-services',
    ]) ?>

    <?= $form->field($model, 'interest_free_period')->textInput([
        'type' => 'text',
        'placeholder' => '120 дней',
        'id' => 'interest-free-period',
    ]) ?>

    <?= $form->field($model, 'payment_partial_repayment')->radioList([
        true => 'Да',
        false => 'Нет'
    ], [
        'id' => 'model-payment-partial-repayment',
    ]) ?>

    <div class="conditional-fields-payment">
        <?= $form->field($model, 'percentage_partial_repayment')->textInput([
            'type' => 'number',
            'placeholder' => '3%',
        ]) ?>
        <?= $form->field($model, 'payment_date_purchase_partial_repayment')->radioList([
            true => 'Да',
            false => 'Нет'
        ], [
            'id' => 'model-payment-date-purchase-partial-repayment',
        ]) ?>

        <div class="conditional-fields-terms-payment">
            <?= $form->field($model, 'conditions_partial_repayment')->textarea([
                'placeholder' => 'После отправки, пожалуйста, ссобщите нам, что вы ваших условий по частичному погашению нет',
                'rows' => 3,
            ]) ?>
        </div>
    </div>

    <?= $form->field($model, 'service_period')->radioList([
        true => 'Год',
        false =>
                    '<span class="tooltip-custom">Месяц
                        <span class="tooltiptext-custom">
                             Если вы указываете в месяц и при этом возврат ДС
                             производится из расчета выписки, то взиматься будет
                             в последний день выписки, в противном случае 30 дней
                         </span>
                    </span>'
    ],[
        'item' => function ($index, $label, $name, $checked, $value) {
            $radioId = $name . '-' . $index;
            $options = [
                'class' => 'form-check-input',
                'id' => $radioId
            ];
            $radio = Html::radio($name, $checked, array_merge(['value' => $value], $options));
            return Html::tag('div',
                Html::label($radio . " " . $label, $radioId, ['class' => 'form-check-label']),
                ['class' => 'form-check']);
        },
        'id' => 'model-service-period'
    ]) ?>

    <?= $form->field($model, 'refund_cash_calculation')->radioList([
        true => 'Из расчета выписки',
        false =>
                    '<span class="tooltip-custom">С даты снятия/покупки
                        <span class="tooltiptext-custom">
                             Если с даты снятия/покупки, то вы будете каждый раз указывать вручную дату
                         </span>
                    </span>'
    ], [
        'item' => function ($index, $label, $name, $checked, $value) {
            $radioId = $name . '-' . $index;
            $options = [
                'class' => 'form-check-input',
                'id' => $radioId
            ];
            $radio = Html::radio($name, $checked, array_merge(['value' => $value], $options));
            return Html::tag('div',
                Html::label($radio . " " . $label, $radioId, ['class' => 'form-check-label']),
                ['class' => 'form-check']);
        },
        'id' => 'model-refund-cash-calculation',
        'class' => ''
    ]) ?>

    <div class="billing-period">
        <?= $form->field($model, 'start_date_billing_period')->input('date') ?>
        <?= $form->field($model, 'end_date_billing_period')->input('date') ?>
    </div>

    <?= $form->field($model, 'note')->textarea(['rows' => 3]) ?>

    <div class="form-group">
        <?= Html::submitButton('Добавить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
