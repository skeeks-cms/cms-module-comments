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
    const PERMISSION_ADD_REVIEW                     = 'comments2.add.review';

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
            'name'          => \skeeks\cms\comments2\Module::t('app', 'Comments'),
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
                            'name'          => \skeeks\cms\comments2\Module::t('app', 'Comments'),
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
            ['enabledBeforeApproval', 'default', 'value' => Cms::BOOL_Y],
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
            'enabledBeforeApproval'                 => \skeeks\cms\comments2\Module::t('app', 'Use pre-moderation comments'),

            'elementPropertyCountCode'              => \skeeks\cms\comments2\Module::t('app', 'Contact number of comments with the property element'),

            'notifyEmails'                          => \skeeks\cms\comments2\Module::t('app', 'Email addresses for notifications'),
            'notifyPhones'                          => \skeeks\cms\comments2\Module::t('app', 'Telephone numbers for notifications'),

            'securityEnabledRateLimit'              => \skeeks\cms\comments2\Module::t('app', 'Enable IP protection'),
            'securityRateLimitRequests'             => \skeeks\cms\comments2\Module::t('app', 'Maximum number of comments for a specified time'),
            'securityRateLimitTime'                 => \skeeks\cms\comments2\Module::t('app', 'The time for that will be taken by the maximum number of comments'),

            'messageSuccessBeforeApproval'          => \skeeks\cms\comments2\Module::t('app', 'Reporting successfully added comments (if the pre-moderation on)'),
            'messageSuccess'                        => \skeeks\cms\comments2\Module::t('app', 'Reporting successfully added comments (without pre-moderation)'),

            'enabledFieldsOnGuest'                  => \skeeks\cms\comments2\Module::t('app', 'The fields in the form to add comments (not authorized)'),
            'enabledFieldsOnUser'                   => \skeeks\cms\comments2\Module::t('app', 'The fields in the form to add comments (user is logged in)'),

            'maxCountMessagesForUser'               => \skeeks\cms\comments2\Module::t('app', 'Maximum number of replies to the same post from one user (0 - unlimited)'),
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