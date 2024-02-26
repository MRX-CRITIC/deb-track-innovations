<?php

/** @var yii\web\View $this */

/** @var string $content */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;

AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<header id="header">
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->user->isGuest ? Yii::$app->urlManager->createUrl(['site/about']) : Yii::$app->homeUrl,
        'options' => ['class' => 'navbar-expand-md navbar-dark bg-dark fixed-top']
    ]);

    $navItems = [];

    if (!Yii::$app->user->isGuest) {
        $navItems[] = ['label' => 'Главная', 'url' => ['/site/index']];
        $navItems[] = ['label' => 'Аналитика', 'url' => ['']];
        $navItems[] = ['label' => 'Аккаунт', 'url' => ['/site/contact']];
        $navItems[] = ['label' => 'О проекте', 'url' => ['/site/about']];
        $navItems[] = '<li class="nav-item">'
            . Html::beginForm(['/user/logout'])
            . Html::submitButton(
                'Выход (' . Yii::$app->user->identity->email . ')',
                ['class' => 'nav-link btn btn-link logout']
            )
            . Html::endForm()
            . '</li>';
    } else {
        $navItems[] = ['label' => 'О проекте', 'url' => ['/site/about']];
        $navItems[] = [
            'label' => 'Войти',
            'items' => [
                ['label' => 'Регистрация', 'url' => ['/user/registration']],
                ['label' => 'Авторизация', 'url' => ['/user/login']],
            ],
            'options' => ['class' => 'nav-item dropdown'],
            'linkOptions' => [
                'class' => 'nav-link dropdown-toggle',
                'id' => 'navbarDropdown',
                'role' => 'button',
                'data-toggle' => 'dropdown',
                'aria-haspopup' => 'true',
                'aria-expanded' => 'false'],
            'url' => ['#'],
            'template' => '<a 
                href="/" 
                class="{linkClass}" 
                id="{linkOptions[id]}"
                >{label}</a>',
        ];
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav'],
        'items' => $navItems,
    ]);
    NavBar::end();
    ?>
</header>

<main id="main" class="flex-shrink-0" role="main">
    <div class="container">
        <?php if (!empty($this->params['breadcrumbs'])): ?>
            <?= Breadcrumbs::widget([
                'homeLink' => ($this->params['homeLink'] !== false) ? [
                    'label' => 'Главная',
                    'url' => Yii::$app->homeUrl,
                ] : ['label' => 'Вход',],
                'links' => $this->params['breadcrumbs'] ?? [],
            ]) ?>
        <?php endif ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</main>

<div>
    <div class="wave"></div>
    <div class="wave"></div>
    <div class="wave"></div>
</div>

<footer id="footer" class="mt-auto py-3 bg-light">
    <div class="container">
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
