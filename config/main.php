<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 15.06.2015
 */
return [
    'components' =>
    [
        'comments2' => [
            'class'         => '\skeeks\cms\comments2\components\Comments2Component',
        ]
    ],

    'modules' =>
    [
        'comments2' => [
            'class'         => '\skeeks\cms\comments2\Module',
        ]
    ]
];