<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 27.05.2015
 */
/* @var $this yii\web\View */
use skeeks\cms\modules\admin\widgets\form\ActiveFormUseTab as ActiveForm;
?>
<?php $form = ActiveForm::begin(); ?>

    <?= $form->fieldSet('Отображение'); ?>
        <?= $form->field($model, 'viewFile')->textInput(); ?>
    <?= $form->fieldSetEnd(); ?>

    <?= $form->fieldSet('Постраничная навигация'); ?>
        <?= $form->fieldRadioListBoolean($model, 'enabledPaging', \Yii::$app->cms->booleanFormat()); ?>
        <?= $form->fieldRadioListBoolean($model, 'enabledPjaxPagination', \Yii::$app->cms->booleanFormat()); ?>
        <?= $form->fieldInputInt($model, 'pageSize'); ?>
        <?= $form->field($model, 'pageParamName')->textInput(); ?>

    <?= $form->fieldSetEnd(); ?>

    <?= $form->fieldSet('Сортировка и количество'); ?>
        <?= $form->fieldInputInt($model, 'limit'); ?>
        <?= $form->fieldSelect($model, 'orderBy', (new \skeeks\cms\comments2\models\Comments2Message())->attributeLabels()); ?>
        <?= $form->fieldSelect($model, 'order', [
            SORT_ASC    => "ASC (от меньшего к большему)",
            SORT_DESC   => "DESC (от большего к меньшему)",
        ]); ?>
    <?= $form->fieldSetEnd(); ?>

    <?= $form->fieldSet('Настройки'); ?>

        <?= $form->field($model, 'btnSubmit')->textInput(); ?>
        <?= $form->field($model, 'btnSubmitClass')->textInput(); ?>

    <?= $form->fieldSetEnd(); ?>
<?= $form->buttonsStandart($model) ?>
<?php ActiveForm::end(); ?>