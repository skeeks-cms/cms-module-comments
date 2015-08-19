<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 14.07.2015
 */

/* @var $this yii\web\View */
/* @var $model \skeeks\cms\models\CmsContentElement */

$search = new \skeeks\cms\models\Search(\skeeks\cms\comments2\models\Comments2Message::className());
$dataProvider = $search->search(\Yii::$app->request->queryParams);

$dataProvider->query->andWhere(['element_id' => $model->id]);
?>

<?= $this->render('@skeeks/cms/comments2/views/admin-message/index', [
    'dataProvider'  => $dataProvider,
    'searchModel'   => $search->getLoadedModel(),
    'controller'    => \Yii::$app->createController('/comments2/admin-message')[0],
    'isOpenNewWindow'    => true
]);
?>