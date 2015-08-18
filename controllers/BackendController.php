<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 15.05.2015
 */
namespace skeeks\cms\comments2\controllers;

use skeeks\cms\components\Cms;
use skeeks\cms\helpers\Request;
use skeeks\cms\helpers\RequestResponse;
use skeeks\cms\modules\admin\actions\modelEditor\AdminOneModelEditAction;
use skeeks\cms\modules\admin\actions\modelEditor\ModelEditorGridAction;
use skeeks\cms\modules\admin\controllers\AdminModelEditorController;
use skeeks\cms\comments2\models\Comments2Message;
use skeeks\modules\cms\form2\models\Form2Form;
use yii\helpers\ArrayHelper;
use yii\web\Controller;

/**
 * Class AdminMessageController
 * @package skeeks\cms\comments2\controllers
 */
class BackendController extends Controller
{
    public function actionSubmit()
    {
        $rr = new RequestResponse();

        $model = new Comments2Message();

        if ($rr->isRequestOnValidateAjaxForm())
        {
            return $rr->ajaxValidateForm($model);
        }

        if ($rr->isRequestAjaxPost())
        {
            $model->scenario = Comments2Message::SCENARIO_SITE_INSERT;

            $model->page_url    = \Yii::$app->request->referrer;
            if ($model->load(\Yii::$app->request->post()))
            {
                //Проверка на максимальное количество комментариев к одному посту от одного пользователя.
                    $messagesFind = Comments2Message::find();
                    if (\Yii::$app->user->isGuest)
                    {
                        $messagesFind->andWhere(['ip' => Request::getRealUserIp()]);
                    } else
                    {
                        $messagesFind->andWhere(['created_by' => \Yii::$app->user->identity->id]);
                    }

                    $messagesFind2 = clone $messagesFind;

                    $messagesFind
                            ->andWhere(['status' => Comments2Message::STATUS_ALLOWED])
                            ->andWhere(['element_id' => $model->element_id])
                    ;

                    if (\Yii::$app->comments2->maxCountMessagesForUser != 0)
                    {
                        if ($messagesFind->count() >= \Yii::$app->comments2->maxCountMessagesForUser)
                        {
                            $rr->success = false;
                            $rr->message = "Вы уже добавляли комментарий к этой записи ранее.";

                            return $rr;
                        }
                    }

                //Проверка частоты добавления комментариев
                if (\Yii::$app->comments2->securityEnabledRateLimit == Cms::BOOL_Y)
                {
                    $messagesFind2 = Comments2Message::find();
                    if (\Yii::$app->user->isGuest)
                    {
                        $messagesFind2->andWhere(['ip' => Request::getRealUserIp()]);
                    } else
                    {
                        $messagesFind2->andWhere(['created_by' => \Yii::$app->user->identity->id]);
                    }

                    $lastTime = \Yii::$app->formatter->asTimestamp(time()) - (int) \Yii::$app->comments2->securityRateLimitTime;

                    $messagesFind2->andWhere([
                        '>=', 'created_at', $lastTime
                    ]);

                    //print_r($messagesFind2->createCommand()->rawSql);die;

                    if ($messagesFind2->count() >= \Yii::$app->comments2->securityRateLimitRequests)
                    {
                        $rr->success = false;
                        $rr->message = "Вы слишком часто добавляете комментарии.";

                        return $rr;
                    }
                }


                if ($model->save())
                {
                    $rr->success = true;

                    if (\Yii::$app->comments2->enabledBeforeApproval == Cms::BOOL_Y)
                    {
                        $rr->message = \Yii::$app->comments2->messageSuccessBeforeApproval;
                    } else
                    {
                        $rr->message        = \Yii::$app->comments2->messageSuccess;

                        //Отключена предмодерация, сразу публикуем
                        $model->status      = Comments2Message::STATUS_ALLOWED;
                        $model->save();
                    }

                    $model->notifyCreate();
                } else
                {

                    $rr->success = false;
                    $rr->message = "Комментарий не добавлен: " . implode(",", $model->getFirstErrors());
                }

            } else
            {
                $rr->success = false;
                $rr->message = "Комментарий не добавлен: " . implode(",", $model->getFirstErrors());
            }
        }

        return $rr;
    }
}