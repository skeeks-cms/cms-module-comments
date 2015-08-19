<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 15.06.2015
 */
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */



$controll = [
    'class'         => \skeeks\cms\modules\admin\grid\ActionColumn::className(),
    'controller'    => $controller
];

if ($isOpenNewWindow)
{
    $controll['isOpenNewWindow'] = true;
}
?>

<?= \skeeks\cms\modules\admin\widgets\GridViewHasSettings::widget([
    'dataProvider'  => $dataProvider,
    'filterModel'   => $searchModel,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],

        $controll,

        [
            'attribute' => 'status',
            'class' => \yii\grid\DataColumn::className(),
            'filter' => \skeeks\cms\comments2\models\Comments2Message::$statuses,
            'format' => 'raw',
            'value' => function(\skeeks\cms\comments2\models\Comments2Message $model)
            {
                if ($model->status == \skeeks\cms\comments2\models\Comments2Message::STATUS_NEW)
                {
                    $class = "default";
                } else if ($model->status == \skeeks\cms\comments2\models\Comments2Message::STATUS_PROCESSED)
                {
                    $class = "warning";
                }  else if ($model->status == \skeeks\cms\comments2\models\Comments2Message::STATUS_CANCELED)
                {
                    $class = "danger";
                } else if ($model->status == \skeeks\cms\comments2\models\Comments2Message::STATUS_ALLOWED)
                {
                    $class = "success";
                }

                return '<span class="label label-' . $class . '">' . \yii\helpers\ArrayHelper::getValue(\skeeks\cms\comments2\models\Comments2Message::$statuses, $model->status) . '</span>';
            }
        ],


        [
            'class' => \skeeks\cms\grid\CreatedAtColumn::className(),
            'label' => 'Добавлен'
        ],
        [
            'class' => \skeeks\cms\grid\CreatedByColumn::className(),
        ],

        [
            'attribute' => 'site_code',
            'class' => \yii\grid\DataColumn::className(),
            'filter' => \yii\helpers\ArrayHelper::map(
                \skeeks\cms\models\CmsSite::find()->all(),
                'code',
                'name'
            ),
            'value' => function(\skeeks\cms\comments2\models\Comments2Message $model)
            {
                return $model->site->name;
            }
        ],

        [
            'filter' => false,
            'attribute' => 'element_id',
            'class' => \yii\grid\DataColumn::className(),
            'value' => function(\skeeks\cms\comments2\models\Comments2Message $model)
            {
                return $model->element->name;
            }
        ],
        [
            'filter' => \yii\helpers\ArrayHelper::map(
                \skeeks\cms\models\CmsContent::find()->all(),
                'id',
                'name'
            ),
            'attribute' => 'content_id',
            'class' => \yii\grid\DataColumn::className(),
            'value' => function(\skeeks\cms\comments2\models\Comments2Message $model)
            {
                return $model->element->cmsContent->name;
            }
        ],

        /*'comments',*/
        /*'user_name',
        'user_email',
        'user_phone',
        'user_city'*/

    ],
]); ?>
