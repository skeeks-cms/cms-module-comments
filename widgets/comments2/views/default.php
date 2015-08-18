<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 07.07.2015
 */
/* @var $this yii\web\View */
/* @var $widget \skeeks\cms\comments2\widgets\comments2\Comments2Widget */

$model = $widget->modelMessage;
$pjaxId = $widget->id . "-pjax";
?>



<? $form = \skeeks\cms\base\widgets\ActiveFormAjaxSubmit::begin([
    'action'        => \skeeks\cms\helpers\UrlHelper::construct('/сomments2/backend/submit')->toString(),
    'validationUrl' => \skeeks\cms\helpers\UrlHelper::construct('/сomments2/backend/submit')->enableAjaxValidateForm()->toString(),
    'afterValidateCallback' => new \yii\web\JsExpression(<<<JS
    function(jQueryForm, AjaxQuery)
    {
        var handler = new sx.classes.AjaxHandlerStandartRespose(AjaxQuery, {
            'blockerSelector' : '#' + jQueryForm.attr('id'),
            'enableBlocker' : true,
        });

        handler.bind('success', function(e, response)
        {
            jQueryForm.empty().append(response.message);
            $.pjax.reload('#{$pjaxId}');
        });

        handler.bind('error', function(e, response)
        {
            $('.sx-captcha-wrapper img', jQueryForm).click();
        });


    }
JS
)
]); ?>

    <?= $form->field($model, 'element_id')->hiddenInput([
        'value' => $widget->cmsContentElement->id
    ])->label(false); ?>


    <?/*= $form->field($model, 'rating')->widget(\kartik\rating\StarRating::classname(), [
        'pluginOptions' => [
            'size' => 'lg',
            'step' => 1,
            'clearCaption' => 'Не выбрано',
            'showClear' => false,
            'starCaptions' =>
            [
                1 => 'Очень плохо',
                2 => 'Плохо',
                3 => 'Нормально',
                4 => 'Хорошо',
                5 => 'Отлично',
            ]
        ]
    ]);*/?>
    <?= $form->field($model, 'rating')->radioList(\Yii::$app->comments2->ratings); ?>

        <? if (\Yii::$app->user->isGuest) : ?>

            <? if (in_array('user_name', \Yii::$app->comments2->enabledFieldsOnGuest)): ?>
                <?= $form->field($model, 'user_name')->textInput(); ?>
            <? endif; ?>

            <? if (in_array('user_email', \Yii::$app->comments2->enabledFieldsOnGuest)): ?>
                <?= $form->field($model, 'user_email')->hint('Email не будет опубликован публично')->textInput(); ?>
            <? endif; ?>

            <? if (in_array('comments', \Yii::$app->comments2->enabledFieldsOnGuest)): ?>
                <?= $form->field($model, 'comments')->textarea([
                    'rows' => 5
                ]); ?>
            <? endif; ?>

            <? if (in_array('dignity', \Yii::$app->comments2->enabledFieldsOnGuest)): ?>
                <?= $form->field($model, 'dignity')->textarea([
                    'rows' => 5
                ]); ?>
            <? endif; ?>

            <? if (in_array('disadvantages', \Yii::$app->comments2->enabledFieldsOnGuest)): ?>
                <?= $form->field($model, 'disadvantages')->textarea([
                    'rows' => 5
                ]); ?>
            <? endif; ?>

            <? if (in_array('verifyCode', \Yii::$app->comments2->enabledFieldsOnGuest)): ?>
                <?= $form->field($model, 'verifyCode')->widget(\skeeks\cms\captcha\Captcha::className()) ?>
            <? endif; ?>

        <? else: ?>

            <? if (in_array('user_name', \Yii::$app->comments2->enabledFieldsOnUser)): ?>
                <?= $form->field($model, 'user_name')->textInput(); ?>
            <? endif; ?>

            <? if (in_array('user_email', \Yii::$app->comments2->enabledFieldsOnUser)): ?>
                <?= $form->field($model, 'user_email')->hint('Email не будет опубликован публично')->textInput(); ?>
            <? endif; ?>

            <? if (in_array('comments', \Yii::$app->comments2->enabledFieldsOnUser)): ?>
                <?= $form->field($model, 'comments')->textarea([
                    'rows' => 5
                ]); ?>
            <? endif; ?>

            <? if (in_array('dignity', \Yii::$app->comments2->enabledFieldsOnUser)): ?>
                <?= $form->field($model, 'dignity')->textarea([
                    'rows' => 5
                ]); ?>
            <? endif; ?>

            <? if (in_array('disadvantages', \Yii::$app->comments2->enabledFieldsOnUser)): ?>
                <?= $form->field($model, 'disadvantages')->textarea([
                    'rows' => 5
                ]); ?>
            <? endif; ?>

            <? if (in_array('verifyCode', \Yii::$app->comments2->enabledFieldsOnUser)): ?>
                <?= $form->field($model, 'verifyCode')->widget(\skeeks\cms\captcha\Captcha::className()) ?>
            <? endif; ?>

        <? endif; ?>


    <?= \yii\helpers\Html::submitButton("" . \Yii::t('app', $widget->btnSubmit), [
        'class' => $widget->btnSubmitClass,
    ]); ?>

<? \skeeks\cms\base\widgets\ActiveFormAjaxSubmit::end(); ?>


<hr />


<? if ($widget->enabledPjaxPagination == \skeeks\cms\components\Cms::BOOL_Y) : ?>
    <? \skeeks\cms\modules\admin\widgets\Pjax::begin([
        'id'        => $pjaxId,
    ]); ?>
<? endif; ?>

    <? echo \yii\widgets\ListView::widget([
        'dataProvider'      => $widget->dataProvider,
        'itemView'          => 'сomment-item',
        'emptyText'          => '',
        'options'           =>
        [
            'tag'   => 'div',
        ],
        'itemOptions' => [
            'tag' => false
        ],
        'layout'            => "\n{items}{$summary}\n<p class=\"row\">{pager}</p>"
    ])?>

<? if ($widget->enabledPjaxPagination == \skeeks\cms\components\Cms::BOOL_Y) : ?>
    <? \skeeks\cms\modules\admin\widgets\Pjax::end(); ?>
<? endif; ?>
