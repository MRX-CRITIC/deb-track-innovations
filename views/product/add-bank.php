<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\BanksForm */
/* @var $banksList app\controllers\ProductController */

$this->title = 'Добавление банка';
$this->params['breadcrumbs'][] = ['label' => 'Добавление карты', 'url' => ['add-card']];
$this->params['breadcrumbs'][] = $this->title;
\app\assets\ProductAsset::register($this);
?>

<div class="site-add-product">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php $form = ActiveForm::begin([
        'id' => 'add-bank-form']); ?>

    <?= $form->field($model, 'user_id')->hiddenInput([
        'value' => Yii::$app->user->identity->id])->label(false) ?>

    <?= $form->field($model, 'name_bank')->input('text', [
        'placeholder' => 'Введите название банка',
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton('Добавить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

