<?php

/** @var yii\web\View $this */
/** @var string $name */
/** @var string $message */

/** @var Exception $exception */

use yii\helpers\Html;

$this->title = 'Ошибка';
\app\assets\IndexAsset::register($this);
?>
<div class="content-row error">

    <div class="product-info">
<!--        --><?//= nl2br(Html::encode($message)) ?>
        <p style="
        font-size: 1.8em;
        color: #8546c5;">
            Произошла непредвиденная ошибка.
        </p>
        <p style="font-size: 0.8rem">
            Пожалуйста, свяжитесь с нами, если вы считаете, что это ошибка сервера.
        </p>


    </div>

    <a class="add-card-href" style="width: 30vh;" href="/site/index">Вернуться на главную</a>


</div>
