<?php

/** @var yii\web\View $this */

/** @var $operationsDataProvider */
/** @var $model */
/** @var $cardsList */

/** @var $selectedCardName */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
use yii\grid\GridView;
use yii\widgets\ListView;
use yii\widgets\LinkPager;
use yii\widgets\Pjax;


$this->title = 'DebTrack Innovations';
\app\assets\ProductAsset::register($this);
\app\assets\IndexAsset::register($this);
\app\assets\OperationAsset::register($this);
?>


<div class="control-panel" style="justify-content: center;">

    <?php $form = ActiveForm::begin([
        'method' => 'get',
        'action' => ['operations'],
        'id' => 'filter-form',

    ]);

    echo $form->field($model, 'name_card')->dropDownList($cardsList, [
        'prompt' => 'Показать все операции',
        'options' => [
            $selectedCardName => ['Selected' => true],
        ],
        'onchange' => 'this.form.submit()',
        'class' => 'custom-dropdown',
    ])->label(false);

    echo $form->field($model, 'date_operation')->input('date', [
        'id' => 'idDateInput',
    ])->label(false); ?>

    <button type="button" class="reset-btn">Сбросить поиск</button>
    <?php ActiveForm::end(); ?>

</div>

<!--     тут операции-->
<div class="all-operations">
    <div class="row" id="operations">
        <table>
            <tr>
                <td></td>
                <td class="title-table-operation">Сумма операции</td>
                <td></td>
            </tr>
            <td>

            </td>
            <?php
            echo ListView::widget([
                'dataProvider' => $operationsDataProvider,
                'itemView' => '_operation', // Путь к шаблону для единичного элемента данных
                'summary' => false,
                'emptyText' => 'Операции отсутствуют',
                'emptyTextOptions' => ['class' => 'empty-text-class'],
                'options' => ['class' => 'list-view'], // Общий контейнер ListView
                'itemOptions' => ['class' => 'item-class'], // Контейнер для элемента данных
                'pager' => [
                    'options' => ['class' => 'custom-pagination'], // Общий контейнер пагинации
                    'linkOptions' => ['class' => 'page-link'], // для каждой ссылки
                    'pageCssClass' => 'page-item', // для каждого номера страницы
                    'prevPageCssClass' => 'page-item prev', // для предыдущей страницы
                    'nextPageCssClass' => 'page-item next', // для следующей страницы
                    'activePageCssClass' => 'active', // для активной страницы
                    'disabledPageCssClass' => 'disabled', // для неактивных кнопок пагинации
                    'maxButtonCount' => 5, // Максимальное количество кнопок страниц
                    'firstPageCssClass' => 'page-item first', // для первой страницы
                    'lastPageCssClass' => 'page-item last', // для последней страницы
                    'firstPageLabel' => false, // Отключить кнопку первой страницы
                    'lastPageLabel' => false, // Отключить кнопку последней страницы
                    'prevPageLabel' => '<img src="/img/back.png" alt="Назад" style="height: 1em;">', // Кнопка предыдущей страницы
                    'nextPageLabel' => '<img src="/img/forward.png" alt="Вперед" style="height: 1em;">', // Кнопка следующей страницы
                    'hideOnSinglePage' => true, // Скрыть пагинацию, если всего одна страница
                ],
            ]);
            ?>
        </table>
    </div>
</div>



