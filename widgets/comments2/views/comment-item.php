<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 10.07.2015
 *
 * @var \skeeks\cms\comments2\models\Comments2Message $model
 */
/* @var $this yii\web\View */

?>

<div class="row margin-bottom-20">
    <div class="col-lg-2">
        <? if ($model->createdBy) : ?>
            <img src="<?= $model->createdBy->getAvatarSrc(); ?>" style="float: left; padding-right: 10px;"/>
            <?= $model->createdBy->displayName; ?>
        <? else : ?>
            <img src="<?= \skeeks\cms\helpers\Image::getCapSrc(); ?>" />
            Гость
        <? endif; ?>
    </div>
    <div class="col-lg-10">
        <p>
            Голос: <?= $model->rating; ?>
        </p>
        <? if ($model->comments) : ?>
            <p>
            <b>Комментарий:</b><br />
            <?= $model->comments; ?>
            </p>
        <? endif; ?>

        <? if ($model->dignity) : ?>
            <p>
            <b>Достоинства:</b><br />
            <?= $model->dignity; ?>
            </p>
        <? endif; ?>

        <? if ($model->disadvantages) : ?>
            <p>
            <b>Недостатки:</b><br />
            <?= $model->disadvantages; ?>
            </p>
        <? endif; ?>
    </div>
    <div class="col-lg-12">
        <hr />
    </div>
</div>
