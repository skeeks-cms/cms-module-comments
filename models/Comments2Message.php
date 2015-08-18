<?php

namespace skeeks\cms\comments2\models;

use skeeks\cms\captcha\CaptchaValidator;
use skeeks\cms\helpers\Request;
use skeeks\cms\models\behaviors\Serialize;
use skeeks\cms\models\behaviors\TimestampPublishedBehavior;
use skeeks\cms\models\CmsContent;
use skeeks\cms\models\CmsContentElement;
use skeeks\cms\models\CmsSite;
use skeeks\cms\models\CmsUser;
use skeeks\cms\models\User;
use Yii;
use yii\db\BaseActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%comments2_message}}".
 *
 * @property integer $id
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $published_at
 * @property integer $processed_at
 * @property integer $processed_by
 *
 * @property integer $element_id
 * @property integer $content_id
 * @property string $dignity
 * @property string $disadvantages
 * @property string $comments
 * @property integer $rating
 * @property integer $status
 * @property string $ip
 * @property string $page_url
 * @property string $data_server
 * @property string $data_session
 * @property string $data_cookie
 * @property string $data_request
 * @property string $site_code
 * @property string $user_name
 * @property string $user_email
 * @property string $user_phone
 * @property string $user_city
 *
 * @property User $processedBy
 * @property CmsContent $content
 * @property User $createdBy
 * @property CmsContentElement $element
 * @property CmsSite $siteCode
 * @property CmsSite $site
 * @property User $updatedBy
 */
class Сomments2Message extends \skeeks\cms\models\Core
{
    public $verifyCode;

    //Сценарий вставки комментариев с сайтовой части + captcha
    const SCENARIO_SITE_INSERT = 'siteInsert';

    const STATUS_NEW            = 0;
    const STATUS_PROCESSED      = 5;
    const STATUS_ALLOWED        = 10;
    const STATUS_CANCELED       = 15;


    static public $statuses =
    [
        self::STATUS_NEW                => "Новый",
        self::STATUS_PROCESSED          => "В обработке",
        self::STATUS_ALLOWED            => "Допущен",
        self::STATUS_CANCELED           => "Отклонен",
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%comments2_message}}';
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->on(BaseActiveRecord::EVENT_AFTER_INSERT,    [$this, "checkDataAfterSave"]);
        $this->on(BaseActiveRecord::EVENT_AFTER_UPDATE,    [$this, "checkDataAfterSave"]);
    }

    /**
     * После сохранения или обновления рейтинга, нужно обновить элемент.
     *
     * @throws \skeeks\cms\relatedProperties\models\InvalidParamException
     */
    public function checkDataAfterSave()
    {
        if (!$this->element)
        {
            return;
        }

        $relatedPropertiesModel = $this->element->relatedPropertiesModel;

        if (!$relatedPropertiesModel)
        {
            return;
        }

        //Выбор всех комментариев принятых к этому элементу, для рассчета рейтинга.
        $messages   = static::find()->where(['element_id' => $this->element->id])->andWhere(['status' => static::STATUS_ALLOWED])->all();

        $count              = 0;
        $ratingSumm         = 0;

        /**
         * @var self $message
         */
        foreach ($messages as $message)
        {
            $count ++;
            $ratingSumm = $ratingSumm + $message->rating;
        }

        if (!$count)
        {
            return ;
        }

        $ratingAll = ($ratingSumm / $count);

        if (\Yii::$app->comments2->elementPropertyCountCode)
        {
            if ($relatedPropertiesModel->hasAttribute(\Yii::$app->comments2->elementPropertyCountCode))
            {
                $relatedPropertiesModel->setAttribute(\Yii::$app->comments2->elementPropertyCountCode, $count);
            }
        }

        if (\Yii::$app->comments2->elementPropertyRatingCode)
        {
            if ($relatedPropertiesModel->hasAttribute(\Yii::$app->comments2->elementPropertyRatingCode))
            {
                $relatedPropertiesModel->setAttribute(\Yii::$app->comments2->elementPropertyRatingCode, $ratingAll);
            }
        }

        $relatedPropertiesModel->save();
    }


    /**
     * @return array
     */
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [

            TimestampPublishedBehavior::className() => TimestampPublishedBehavior::className(),
            Serialize::className() =>
            [
                'class' => Serialize::className(),
                'fields' => ['data_server', 'data_session', 'data_cookie', 'data_request']
            ],

        ]);
    }

    /**
     * @return array
     */
    public function scenarios()
    {
        $result                                 = parent::scenarios();
        $result[self::SCENARIO_SITE_INSERT]     = ArrayHelper::merge($result[self::SCENARIO_DEFAULT], [
            'verifyCode'
        ]);
        return $result;
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_by', 'updated_by', 'created_at', 'updated_at', 'element_id', 'content_id', 'status'], 'integer'],
            [['element_id', 'rating'], 'required'],
            [['dignity', 'disadvantages', 'comments'], 'string'],
            [['rating'], 'integer', 'min' => 1, 'max' => (int) \Yii::$app->comments2->maxValue],
            [['ip'], 'string', 'max' => 32],
            [['page_url'], 'string'],
            [['site_code'], 'string', 'max' => 15],
            [['user_name', 'user_email', 'user_phone', 'user_city'], 'string', 'max' => 255],

            [['status'], 'in', 'range' => array_keys(self::$statuses)],

            ['site_code', 'default', 'value' => \Yii::$app->cms->site->code],

            ['published_at', 'integer'],
            ['processed_at', 'integer'],
            ['processed_by', 'integer'],

            ['user_email', 'email'],

            ['data_request', 'default', 'value' => $_REQUEST],
            ['data_server', 'default', 'value' => $_SERVER],
            ['data_cookie', 'default', 'value' => $_COOKIE],
            ['data_session', 'default', 'value' => function(self $model, $attribute)
            {
                \Yii::$app->session->open();
                return $_SESSION;
            }],
            ['content_id', 'default', 'value' => function(self $model, $attribute)
            {
                return $model->element->cmsContent->id;
            }],
            ['ip', 'default', 'value' => Request::getRealUserIp()],

            [
                'verifyCode',
                CaptchaValidator::className(),
                'skipOnEmpty'   =>  $this->_skipOnEmptyVerifyCode(),
                'on'            => self::SCENARIO_SITE_INSERT
            ],
        ];
    }

    protected function _skipOnEmptyVerifyCode()
    {
        if (\Yii::$app->user->isGuest && in_array('verifyCode', \Yii::$app->comments2->enabledFieldsOnGuest))
        {
            return false;
        }

        if (!\Yii::$app->user->isGuest && in_array('verifyCode', \Yii::$app->comments2->enabledFieldsOnUser))
        {
            return false;
        }

        return true;
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'published_at' => Yii::t('app', 'Время публикации'),
            'element_id' => Yii::t('app', 'Элемент'),
            'content_id' => Yii::t('app', 'Тип контента'),
            'dignity' => Yii::t('app', 'Достоинства'),
            'disadvantages' => Yii::t('app', 'Недостатки'),
            'comments' => Yii::t('app', 'Комментарий'),
            'rating' => Yii::t('app', 'Rating'),
            'status' => Yii::t('app', 'Status'),
            'ip' => Yii::t('app', 'Ip'),
            'page_url' => Yii::t('app', 'Page Url'),
            'data_server' => Yii::t('app', 'Data Server'),
            'data_session' => Yii::t('app', 'Data Session'),
            'data_cookie' => Yii::t('app', 'Data Cookie'),
            'data_request' => Yii::t('app', 'Data Request'),
            'site_code' => Yii::t('app', 'Сайт'),
            'user_name' => Yii::t('app', 'Имя'),
            'user_email' => Yii::t('app', 'Email'),
            'user_phone' => Yii::t('app', 'Phone'),
            'user_city' => Yii::t('app', 'City'),
            'processed_at' => Yii::t('app', 'Когда обратали'),
            'processed_by' => Yii::t('app', 'Кто обработал'),
            'verifyCode' => Yii::t('app', 'Verification Code'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProcessedBy()
    {
        return $this->hasOne(CmsUser::className(), ['id' => 'processed_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContent()
    {
        return $this->hasOne(CmsContent::className(), ['id' => 'content_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(CmsUser::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getElement()
    {
        return $this->hasOne(CmsContentElement::className(), ['id' => 'element_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSiteCode()
    {
        return $this->hasOne(CmsSite::className(), ['code' => 'site_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSite()
    {
        return $this->hasOne(CmsSite::className(), ['code' => 'site_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(CmsUser::className(), ['id' => 'updated_by']);
    }




    /**
     * Уведомить всех кого надо и как надо
     */
    public function notifyCreate()
    {
        if (\Yii::$app->comments2->notifyEmails)
        {
            foreach (\Yii::$app->comments2->notifyEmails as $email)
            {
                \Yii::$app->mailer->compose('@skeeks/cms/comments2/mail/new-message', [
                    'model'          => $this
                ])
                ->setFrom([\Yii::$app->cms->adminEmail => \Yii::$app->cms->appName])
                ->setTo($email)
                ->setSubject("Добавлен новый комментарий #" . $this->id)
                ->send();
            }
        }
    }
}
