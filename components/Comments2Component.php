<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 03.07.2015
 */
namespace skeeks\cms\comments2\components;
use skeeks\cms\base\Component;
use skeeks\cms\components\Cms;
use skeeks\cms\controllers\AdminCmsContentElementController;
use skeeks\cms\modules\admin\actions\modelEditor\AdminOneModelFilesAction;
use skeeks\cms\modules\admin\controllers\AdminController;
use skeeks\cms\modules\admin\controllers\events\AdminInitEvent;
use skeeks\cms\comments2\actions\AdminOneModelMessagesAction;
use yii\helpers\ArrayHelper;

/**
 *
 * Class Comments2Component
 * @package skeeks\cms\comments2\components
 */
class Comments2Component extends Component
{
    public $enabledBeforeApproval                   = Cms::BOOL_Y;

    public $maxCountMessagesForUser                 = 1;

    public $elementPropertyCountCode                = "comments2_count";

    public $notifyEmails                            = [];
    public $notifyPhones                            = [];

    public $securityEnabledRateLimit                = Cms::BOOL_Y;
    public $securityRateLimitRequests               = 10;
    public $securityRateLimitTime                   = 3600;

    public $messageSuccessBeforeApproval            = "Комментарий успешно добавлен, и будет опубликован на сайте после проверки модератора.";
    public $messageSuccess                          = "Комментарий успешно добавлен, спасибо.";

    public $enabledFieldsOnUser                     = ['comments'];
    public $enabledFieldsOnGuest                    = ['comments', 'user_email', 'user_name', 'verifyCode'];

    /**
     * Можно задать название и описание компонента
     * @return array
     */
    static public function descriptorConfig()
    {
        return array_merge(parent::descriptorConfig(), [
            'name'          => 'Комментарии',
        ]);
    }

    public function init()
    {
        parent::init();

        \Yii::$app->on(AdminController::EVENT_INIT, function (AdminInitEvent $e) {

            if ($e->controller instanceof AdminCmsContentElementController)
            {
                $e->controller->eventActions = ArrayHelper::merge($e->controller->eventActions, [
                    'comments2' =>
                        [
                            'class'         => AdminOneModelMessagesAction::className(),
                            'name'          => 'Комментарии',
                            'priority'      => 1000,
                        ],
                ]);
            }
        });
    }


    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [['enabledBeforeApproval'], 'string'],
            [['securityRateLimitRequests'], 'integer'],
            [['securityRateLimitTime'], 'integer'],
            [['elementPropertyCountCode'], 'string'],
            [['messageSuccessBeforeApproval'], 'string'],
            [['messageSuccess'], 'string'],
            [['notifyEmails'], 'safe'],
            [['notifyPhones'], 'safe'],
            [['maxCountMessagesForUser'], 'integer'],
            [['enabledFieldsOnGuest'], 'safe'],
            [['enabledFieldsOnUser'], 'safe'],
            [['securityEnabledRateLimit'], 'string'],
            [['enabledBeforeApproval', 'securityEnabledRateLimit'], 'in', 'range' => array_keys(\Yii::$app->cms->booleanFormat())],
        ]);
    }

    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'enabledBeforeApproval'                 => 'Использовать премодерацию комментариев',

            'elementPropertyCountCode'              => 'Связь количества комментариев со свойством элемента',

            'notifyEmails'                          => 'Email адреса для уведомлений',
            'notifyPhones'                          => 'Телефонные номера для уведомлений',

            'securityEnabledRateLimit'              => 'Включить защиту по IP',
            'securityRateLimitRequests'             => 'Максимальное количество комментариев',
            'securityRateLimitTime'                 => 'Время за которое будет размещено максимальное количество комментариев',

            'messageSuccessBeforeApproval'          => 'Сообщение об успешно добавленном комментарии (если включена предмодерация)',
            'messageSuccess'                        => 'Сообщение об успешно добавленном комментарии (без предмодерации)',

            'enabledFieldsOnGuest'                  => 'Поля в форме добавления комментария (пользователь неавторизован)',
            'enabledFieldsOnUser'                   => 'Поля в форме добавления комментария (пользователь авторизован)',

            'maxCountMessagesForUser'               => 'Максимальное количество комментариев к одному посту от одного польозвателя (0 - неограничено)',
        ]);
    }

    /**
     * @return array

    public function getRatings()
    {
        for($i >= 1; $i <= $this->maxValue; $i ++)
        {
            $result[$i] = $i;
        }

        foreach ($result as $key => $value)
        {
            if (!$value)
            {
                unset($result[$key]);
            }
        }

        return $result;
    }*/
}