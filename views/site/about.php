<?php

/** @var yii\web\View $this */

use yii\helpers\Html;

$this->title = 'О пректе';
!Yii::$app->user->isGuest ? $this->params['breadcrumbs'][] = $this->title : '';
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="jumbotron text-center bg-transparent mt-5 mb-5">
        <h1 class="display-4">Добро пожаловать!</h1>
        <p class="lead">Проект поможет вам отслеживать внесение платежей по одному и более кредитному продукту</p>
    </div>


</div>
