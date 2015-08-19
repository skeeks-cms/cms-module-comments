<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 15.05.2015
 */
namespace skeeks\cms\comments2\controllers;

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
        $this->name                     = "Управление комментариями";
        $this->modelShowAttribute       = "id";
        $this->modelClassName           = Comments2Message::className();

        parent::init();
    }
}