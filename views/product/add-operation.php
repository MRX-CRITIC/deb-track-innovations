<?php

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
use yii\helpers\Url;

/** @var yii\web\View $this */
/* @var $model app\models\OperationsForm */
/* @var $card_id app\controllers\ProductController */
/* @var $fin_balance app\controllers\ProductController */

$this->title = 'Добавление операции';
$this->params['breadcrumbs'][] = $this->title;

\app\assets\ProductAsset::register($this);
\app\assets\IndexAsset::register($this);
?>

<div class="site-product">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin([
        'id' => 'add-operation-form']); ?>


    <div class="date_operation">
        <?= $form->field($model, 'date_operation')->input('date') ?>
    </div>

    <div class="type_operation">
        <?= $form->field($model, 'type_operation')->radioList([
            false => 'Снятие/покупка',
            true => 'Внесение'
        ], ['id' => 'type_operation']) ?>
    </div>

    <?= $form->field($model, 'sum')->textInput([
        'type' => 'integer',
        'placeholder' => '100000',
        'id' => 'sum',
    ]) ?>

    <?= $form->field($model, 'note')->textarea(['rows' => 3]) ?>

    <div class="form-group">
        <?= Html::submitButton('Добавить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

