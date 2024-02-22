<?php
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Breadcrumbs;

/* @var $this yii\web\View */
/* @var $model app\models\CardsForm */

$this->title = 'Добавление карты';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="card-add">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php $form = ActiveForm::begin(['id' => 'card-form']); ?>

    <?= $form->field($model, 'bank_id')->dropdownList([], ['prompt' => 'Выберите банк']) ?>
    <?= $form->field($model, 'name_card')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'credit_limit')->textInput(['type' => 'number']) ?>
    <?= $form->field($model, 'cost_banking_services')->textInput(['type' => 'number']) ?>
    <?= $form->field($model, 'interest_free_period')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'payment_partial_repayment')->radioList([
        'yes' => 'Да',
        'no' => 'Нет',
    ]) ?>
    <?= $form->field($model, 'percentage_partial_repayment')->textInput(['type' => 'number']) ?>
    <?= $form->field($model, 'conditions_partial_repayment')->textarea(['rows' => 3]) ?>
    <?= $form->field($model, 'service_period')->radioList([
        'year' => 'Год',
        'month' => 'Месяц',
    ]) ?>
    <?= $form->field($model, 'refund_cash_calculation')->radioList([
        'end_of_period' => 'Из расчета вилсиска',
        'date_of_purchase' => 'С даты снятия или покупки',
    ]) ?>
    <?= $form->field($model, 'start_date_billing_period')->textInput(['type' => 'date']) ?>
    <?= $form->field($model, 'end_date_billing_period')->textInput(['type' => 'date']) ?>
    <?= $form->field($model, 'note')->textarea(['rows' => 3]) ?>

    <div class="form-group">
        <?= Html::submitButton('Добавить', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
