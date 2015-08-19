<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 19.03.2015
 */
use skeeks\cms\mail\helpers\Html;
/**
 * @var $model \skeeks\cms\comments2\models\Comments2Message
 */
?>
<?= Html::beginTag('h1'); ?>
    Добавлен новый комментарий #<?= $model->id; ?>
<?= Html::endTag('h1'); ?>

<?= Html::beginTag('p'); ?>
    Комментарий успешно отправлен со страницы: <?= Html::a($model->page_url, $model->page_url); ?><br />
    Дата и время отправки: <?= \Yii::$app->formatter->asDatetime($model->created_at) ?><br />
    Уникальный номер комментария: <?= $model->id; ?>
<?= Html::endTag('p'); ?>

<?= Html::beginTag('h3'); ?>
    Данные комментария:
<?= Html::endTag('h3'); ?>

<?= Html::beginTag('p'); ?>


    <?= \yii\widgets\DetailView::widget([
        'model'         => $model,
        'attributes'    =>
        [
            'id',
            'comments'
        ]
    ])?>

<?= Html::endTag('p'); ?>


<?= Html::beginTag('p'); ?>
    Для управления комментариями используйте инструмент: <?= Html::a('тут', \skeeks\cms\helpers\UrlHelper::construct('comments2/admin-message/update', ['pk' => $model->id])->enableAdmin()->enableAbsolute()->toString()); ?>.
<?= Html::endTag('p'); ?>