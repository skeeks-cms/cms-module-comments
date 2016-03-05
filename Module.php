<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 03.07.2015
 */
namespace skeeks\cms\comments2;
/**
 * Class Module
 * @package skeeks\cms\comments2
 */
class Module extends \skeeks\cms\base\Module
{
    public $controllerNamespace = 'skeeks\cms\comments2\controllers';

    /**
     * @return array
     */
    static public function descriptorConfig()
    {
        return array_merge(parent::descriptorConfig(), [
            "version"               => file_get_contents(__DIR__ . "/VERSION"),

            "name"          => "Комментарии",
        ]);
    }

    public static function t($category, $message, $params = [], $language = null)
    {
        return \Yii::t('skeeks/comments2/' . $category, $message, $params, $language);
    }

}