<?php

/** @var yii\web\View $this */

use yii\helpers\Html;

$this->title = 'О пректе';
!Yii::$app->user->isGuest ? $this->params['breadcrumbs'][] = $this->title : '';

\app\assets\ProductAsset::register($this);
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="jumbotron text-center bg-transparent mt-5 mb-5">
        <h1 class="display-4">Добро пожаловать!</h1>
        <p class="lead">Проект поможет вам отслеживать внесение платежей по одному и более кредитному продукту</p>
        <p class="lead">Для снятия или перевода денежных средств</p>
    </div>

    <div class="content-info">
        <div class="paragraph">
            1. Чтобы начать использовать возможности проекта,
            для начала Вам потребуется добавить карту со всеми ее условиями обслуживания
            <img class="img-site" src="<?= Yii::getAlias('@web') ?> /img/about/add-card.png" alt="">
        </div>

        <div class="paragraph">
            2. При необходимости вы имеете возможность добавить свой банк
            <br><br><img class="img-site" src="<?= Yii::getAlias('@web') ?> /img/about/add-bank.png" alt="">
        </div>

        <div class="paragraph">
            3. После добавления карт(ы) вы имеете главную информацию о карте
            <br><br><img class="img-site" src="<?= Yii::getAlias('@web') ?> /img/about/index.png" alt="">
        </div>
    </div>

</div>
