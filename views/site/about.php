<?php

/** @var yii\web\View $this */

use yii\helpers\Html;

$this->title = 'О проекте';
!Yii::$app->user->isGuest ? $this->params['breadcrumbs'][] = $this->title : '';

\app\assets\ProductAsset::register($this);
?>

<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="jumbotron text-center bg-transparent mt-5 mb-5">
        <h1 class="display-4">Добро пожаловать<br> в сервис DebTrack Innovations!</h1>
        <p class="lead">Наш сервис упрощает управление вашими кредитными продуктами, позволяя легко отслеживать все платежи.</p>
        <h1 class="lead">Для снятия или перевода денежных средств</h1>
    </div>

    <div class="content-info">
        <div class="paragraph">
            1. Начните с добавления вашей карты и ее условий обслуживания.
            <br><br><img class="img-site" src="<?= Yii::getAlias('@web') ?> /img/about/add-card.png" alt="">
        </div>

        <div class="paragraph">
            2. При необходимости вы имеете возможность добавить свой банк
            <br><br><img class="img-site" src="<?= Yii::getAlias('@web') ?> /img/about/add-bank.png" alt="">
        </div>

        <div class="paragraph">
            3. После добавления карт(ы) важная информация о ней отражается на главной страницы сервиса
            <br><br><img class="img-site" src="<?= Yii::getAlias('@web') ?> /img/about/index.png" alt="">
        </div>

        <div class="paragraph">
            4. Вся более подробная информация о карте всегда в доступе и доступна для редактирования или удаления
            <br><br><img class="img-site" src="<?= Yii::getAlias('@web') ?> /img/about/info-card.png" alt="">
        </div>

        <div class="paragraph">
            5. Каждый раз когда вы совершаете какие либо операции
            (подразумевается перевод или снятия/внесение денежных средств)
            с картой Вам необходимо дублировать те же операции в нашем сервисе
            <br><img class="img-site" src="<?= Yii::getAlias('@web') ?> /img/about/add-operation.png" alt="">
        </div>

        <div class="paragraph">
            6. В разделе операции есть полный доступ к истории всех операций.
            С возможностью последовательного удаления и фильтрации
            <br><br><br><img class="img-site" src="<?= Yii::getAlias('@web') ?> /img/about/operation.png" alt="">
        </div>

        <div class="paragraph">
            7. Как напоминание, сервис автоматически отправит вам уведомление
            за день до необходимости внесения следующего платежа, чтобы вы не пропустили ни одного срока.
            <br><br><img class="img-site" src="<?= Yii::getAlias('@web') ?> /img/about/info-message.png" alt="">
        </div>

    </div>
</div>
