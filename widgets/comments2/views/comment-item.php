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
    <div class="col-lg-2 col-md-2">
        <? if ($model->createdBy) : ?>
            <img src="<?= $model->createdBy->getAvatarSrc(); ?>" />
        <? else : ?>
            <img src="<?= \skeeks\cms\helpers\Image::getCapSrc(); ?>" />
        <? endif; ?>
    </div>
    <div class="col-lg-10 col-md-10">
        <? if ($model->createdBy) : ?>
            <strong><?= $model->createdBy->displayName; ?></strong>
            <small>(добавлен <?= Yii::$app->formatter->asDatetime($model->created_at); ?>):</small><br /><br>
        <? else : ?>
            Гость
        <? endif; ?>
        <? if ($model->comments) : ?>
            <p>
            <?= $model->comments; ?>
            </p>
        <? endif; ?>

    </div>
</div>
    <hr />