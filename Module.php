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
class Module extends \yii\base\Module
{
    public $controllerNamespace = 'skeeks\cms\comments2\controllers';

    public static function t($category, $message, $params = [], $language = null)
    {
        return \Yii::t('skeeks/comments2/' . $category, $message, $params, $language);
    }

}
