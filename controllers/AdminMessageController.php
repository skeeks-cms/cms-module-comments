<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 15.05.2015
 */
namespace skeeks\cms\comments2\controllers;

use skeeks\cms\modules\admin\actions\modelEditor\AdminMultiModelEditAction;
use skeeks\cms\modules\admin\actions\modelEditor\AdminOneModelEditAction;
use skeeks\cms\modules\admin\actions\modelEditor\ModelEditorGridAction;
use skeeks\cms\modules\admin\controllers\AdminModelEditorController;
use skeeks\modules\cms\form2\models\Form2Form;
use skeeks\cms\comments2\models\Comments2Message;
use yii\helpers\ArrayHelper;

/**
 * Class AdminMessageController
 * @package skeeks\cms\comments2\controllers
 */
class AdminMessageController extends AdminModelEditorController
{
    public function init()
    {
        $this->name                     = \skeeks\cms\comments2\Module::t('app', 'Managing comments');
        $this->modelShowAttribute       = "id";
        $this->modelClassName           = Comments2Message::className();

        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return ArrayHelper::merge(parent::actions(),
            [
                'index' =>
                [
                    'columns' => [
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
                            'attribute' => 'element_id',
                            'relation' => 'element',
                            'class' => \skeeks\cms\grid\CmsContentElementColumn::className(),
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
                ],
                ],

                "status-allowed-multi" =>
                [
                    'class' => AdminMultiModelEditAction::className(),
                    "name" => \skeeks\cms\comments2\Module::t('app', 'Approve'),
                    //"icon"              => "glyphicon glyphicon-trash",
                    "eachCallback" => [$this, 'eachMultiStatusAllowed'],
                ],

                "status-canceled-multi" =>
                [
                    'class' => AdminMultiModelEditAction::className(),
                    "name" => \skeeks\cms\comments2\Module::t('app', 'Reject'),
                    //"icon"              => "glyphicon glyphicon-trash",
                    "eachCallback" => [$this, 'eachMultiStatusCanceled'],
                ],

                "status-processed-multi" =>
                [
                    'class' => AdminMultiModelEditAction::className(),
                    "name" => \skeeks\cms\comments2\Module::t('app', 'In progress'),
                    //"icon"              => "glyphicon glyphicon-trash",
                    "eachCallback" => [$this, 'eachMultiStatusProcessed'],
                ],
            ]
        );
    }


    /**
     * @param $model
     * @param $action
     * @return bool
     */
    public function eachMultiStatusAllowed($model, $action)
    {
        try
        {
            $model->status = Comments2Message::STATUS_ALLOWED;
            return $model->save(false);
        } catch (\Exception $e)
        {
            return false;
        }
    }
    /**
     * @param $model
     * @param $action
     * @return bool
     */
    public function eachMultiStatusCanceled($model, $action)
    {
        try
        {
            $model->status = Comments2Message::STATUS_CANCELED;
            return $model->save(false);
        } catch (\Exception $e)
        {
            return false;
        }
    }

    /**
     * @param $model
     * @param $action
     * @return bool
     */
    public function eachMultiStatusProcessed($model, $action)
    {
        try
        {
            $model->status = Comments2Message::STATUS_PROCESSED;
            return $model->save(false);
        } catch (\Exception $e)
        {
            return false;
        }
    }
}