<?php

use yii\helpers\Html;
use skeeks\cms\modules\admin\widgets\form\ActiveFormUseTab as ActiveForm;
/* @var $this yii\web\View */
/* @var $action \skeeks\cms\modules\admin\actions\modelEditor\AdminOneModelEditAction */
/* @var $model \skeeks\modules\cms\form2\models\Form2FormSend */
$model = $action->controller->model;

if ($model->isNewRecord !== null)
{
    if ($model->status == \skeeks\cms\comments2\models\Comments2Message::STATUS_NEW && !$model->processed_by)
    {
        $model->processed_by = \Yii::$app->user->identity->id;
        $model->processed_at = \Yii::$app->formatter->asTimestamp(time());
        $model->status = \skeeks\cms\comments2\models\Comments2Message::STATUS_PROCESSED;

        $model->save();
    }
}


?>

<? $form = ActiveForm::begin(); ?>

<?= $form->fieldSet('Основная информация'); ?>


    <?= $form->fieldSelect($model, 'rating', \Yii::$app->comments2->ratings); ?>
    <?= $form->field($model, 'comments')->textarea(['rows' => 5]); ?>
    <?= $form->field($model, 'dignity')->textarea(['rows' => 5]); ?>
    <?= $form->field($model, 'disadvantages')->textarea(['rows' => 5]); ?>

<?= $form->fieldSetEnd(); ?>

<?= $form->fieldSet('Автор'); ?>
    <?= $form->fieldSelect($model, 'created_by', \yii\helpers\ArrayHelper::map(
            \skeeks\cms\models\User::find()->active()->all(),
            'id',
            'displayName'
        )) ?>

    <?= $form->field($model, 'user_name')->textInput(); ?>
    <?= $form->field($model, 'user_email')->textInput(); ?>
    <?= $form->field($model, 'user_phone')->textInput(); ?>
    <?= $form->field($model, 'user_city')->textInput(); ?>
<?= $form->fieldSetEnd(); ?>

<?= $form->fieldSet('Обработка'); ?>
    <?= $form->fieldSelect($model, 'status', \skeeks\cms\comments2\models\Comments2Message::$statuses); ?>

    <?= $form->fieldSelect($model, 'processed_by', \yii\helpers\ArrayHelper::map(
            \skeeks\cms\models\User::find()->active()->all(),
            'id',
            'displayName'
        )); ?>

    <?= $form->field($model, 'published_at')->widget(\kartik\datecontrol\DateControl::classname(), [
        //'displayFormat' => 'php:d-M-Y H:i:s',
        'type' => \kartik\datecontrol\DateControl::FORMAT_DATETIME,
    ]); ?>


<?= $form->fieldSetEnd(); ?>

<?= $form->fieldSet('Дополнительная информация'); ?>
    <?= \yii\widgets\DetailView::widget([
        'model'         => $model,
        'attributes'    =>
        [
            [
                'attribute'     => 'id',
                'label'         => 'Номер сообщения',
            ],

            [
                'attribute' => 'created_at',
                'value' => \Yii::$app->formatter->asDatetime($model->created_at, 'medium') . "(" . \Yii::$app->formatter->asRelativeTime($model->created_at) . ")",
            ],

            [
                'format' => 'raw',
                'label' => 'Отправлено с сайта',
                'value' => "<a href=\"{$model->site->url}\" target=\"_blank\" data-pjax=\"0\">{$model->site->name}</a>",
            ],

            [
                'format' => 'raw',
                'label' => 'Отправил пользователь',
                'value' => "{$model->createdBy->displayName}",
            ],

            [
                'attribute' => 'ip',
                'label' => 'Ip адрес отправителя',
            ],

            [
                'attribute' => 'page_url',
                'format' => 'raw',
                'label' => 'Отправлена со страницы',
                'value' => Html::a($model->page_url, $model->page_url, [
                    'target' => '_blank',
                    'data-pjax' => 0
                ])
            ],
        ]
    ]); ?>

<?= $form->fieldSetEnd(); ?>



<?= $form->fieldSet('Для разработчиков'); ?>

<div class="sx-block">
  <h3>Дополнительные данные, которые могут пригодиться в некоторых случаях, разработчикам.</h3>
  <small>Для удобства просмотра данных, можно воспользоваться сервисом: <a href="http://jsonformatter.curiousconcept.com/#" target="_blank">http://jsonformatter.curiousconcept.com/#</a></small>
</div>
<hr />


    <?= \yii\widgets\DetailView::widget([
        'model'         => $model,
        'attributes'    =>
        [
            [
                'attribute' => 'data_server',
                'format' => 'raw',
                'label' => 'SERVER',
                'value' => "<textarea class='form-control' rows=\"10\">" . \yii\helpers\Json::encode($model->data_server) . "</textarea>"
            ],

            [
                'attribute' => 'data_cookie',
                'format' => 'raw',
                'label' => 'COOKIE',
                'value' => "<textarea class='form-control' rows=\"5\">" . \yii\helpers\Json::encode($model->data_cookie) . "</textarea>"
            ],

            [
                'attribute' => 'data_session',
                'format' => 'raw',
                'label' => 'SESSION',
                'value' => "<textarea class='form-control' rows=\"5\">" . \yii\helpers\Json::encode($model->data_session) . "</textarea>"
            ],

            [
                'attribute' => 'data_request',
                'format' => 'raw',
                'label' => 'REQUEST',
                'value' => "<textarea class='form-control' rows=\"10\">" . \yii\helpers\Json::encode($model->data_request) . "</textarea>"
            ],

        ]
    ]); ?>

<?= $form->fieldSetEnd(); ?>

<?= $form->buttonsStandart($model); ?>

<? ActiveForm::end(); ?>
