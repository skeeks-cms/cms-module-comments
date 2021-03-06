<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 12.03.2015
 */
return [
    'other' =>
    [
        'items' =>
        [
            [
                "label"     => \skeeks\cms\comments2\Module::t('app', 'Comments'),
                "img"       => ['\skeeks\cms\comments2\assets\Comments2Asset', 'icons/comments.jpg'],

                'items' =>
                [
                    [
                        "label" => \skeeks\cms\comments2\Module::t('app', 'Comments'),
                        "url"   => ["comments2/admin-message"],
                        "img"       => ['\skeeks\cms\comments2\assets\Comments2Asset', 'icons/comments.jpg'],
                    ],

                    [
                        "label" => \skeeks\cms\comments2\Module::t('app', 'Settings'),
                        "url"   => ["cms/admin-settings", "component" => 'skeeks\cms\comments2\components\Comments2Component'],
                        "img"       => ['\skeeks\cms\modules\admin\assets\AdminAsset', 'images/icons/settings.png'],
                        "activeCallback"       => function(\skeeks\cms\modules\admin\helpers\AdminMenuItem $adminMenuItem)
                        {
                            return (bool) (\Yii::$app->request->getUrl() == $adminMenuItem->getUrl());
                        },
                    ],

                ]
            ],
        ]
    ]

];