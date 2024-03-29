<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\CardsForm */
/* @var $banksList app\controllers\ProductController */

$this->title = 'Добавление карты';
$this->params['breadcrumbs'][] = $this->title;
\app\assets\ProductAsset::register($this);
?>
<div class="site-product">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin([
        'id' => 'add-card-form',
    ]); ?>

    <?= $form->field($model, 'bank_id')->dropDownList($banksList, [
        'prompt' => 'Выберите банк',
        'id' => 'bank-select',
    ]) ?>

    <?= $form->field($model, 'name_card')->textInput([
        'placeholder' => 'Не обязательно',
        'id' => 'name-card',
    ]) ?>

    <?= $form->field($model, 'credit_limit')->textInput([
        'type' => 'number',
        'placeholder' => '100000',
        'id' => 'credit-limit',
        'pattern' => '\d*',
    ]) ?>

    <?= $form->field($model, 'withdrawal_limit')->textInput([
        'type' => 'number',
        'placeholder' => 'Не обязательно',
        'id' => 'withdrawal-limit',
        'pattern' => '[0-9,]*',
        'inputmode' => 'decimal',
    ]) ?>

    <?= $form->field($model, 'cost_banking_services')->textInput([
        'type' => 'number',
        'placeholder' => '990',
        'id' => 'cost-banking-services',
        'pattern' => '\d*',
    ]) ?>

    <?= $form->field($model, 'grace_period')->textInput([
        'type' => 'text',
        'placeholder' => '120 дней',
        'id' => 'interest-free-period',
        'inputmode' => 'numeric',
    ]) ?>

    <?= $form->field($model, 'payment_partial_repayment')->radioList([
        true => 'Да',
        false => 'Нет'
    ], [
        'id' => 'model-payment-partial-repayment',
    ]) ?>

    <div class="conditional-fields-payment">
        <?= $form->field($model, 'percentage_partial_repayment')->textInput([
            'type' => 'text',
            'placeholder' => '3 %',
            'id' => 'percentage-partial-repayment',
            'inputmode' => 'decimal',
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
                'id' => 'conditions-partial-repayment',
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
        'id' => 'model-service-period'
    ]) ?>

    <div class="date_annual_service">
        <?= $form->field($model, 'date_annual_service')->input('date', ['id' => 'date-annual-service']) ?>
        <div class="message-annual-service">
            Год укажите текущий, если в этом году списание уже было, то укажите ее.
        </div>
    </div>

    <?= $form->field($model, 'refund_cash_calculation')->radioList([
        true => 'Из расчета выписки',
        false => 'С даты снятия/покупки'
    ],
        [
            'id' => 'model-refund-cash-calculation',
            'class' => ''
        ]) ?>

    <div class="billing-period">
        <?= $form->field($model, 'start_date_billing_period')->input('date', ['id' => 'start-date']) ?>
        <?= $form->field($model, 'end_date_billing_period')->input('date', ['id' => 'end-date']) ?>
    </div>

    <?= $form->field($model, 'note')->textarea(['rows' => 3]) ?>

    <div class="form-group">
        <?= Html::submitButton('Добавить', [
            'class' => 'btn btn-success',
            'id' => 'btn-add-card'
        ]) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
