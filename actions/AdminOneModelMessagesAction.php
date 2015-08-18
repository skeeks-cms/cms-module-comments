<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 14.07.2015
 */
namespace skeeks\cms\comments2\actions;
use skeeks\cms\modules\admin\actions\modelEditor\AdminOneModelEditAction;

/**
 * Class CommentsAsset
 * @package skeeks\cms\comments2\assets
 */
class AdminOneModelMessagesAction extends AdminOneModelEditAction
{
    /**
     * Renders a view
     *
     * @param string $viewName view name
     * @return string result of the rendering
     */
    protected function render($viewName)
    {
        $this->viewParams =
        [
            'model' => $this->controller->model
        ];

        return $this->controller->render("@skeeks/cms/comments2/actions/views/messages", (array) $this->viewParams);
    }

}
