Error logging in mysql database for SkeekS CMS
===================================

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist skeeks/cms-log-db-target "*"
```

or add

```
"skeeks/cms-log-db-target": "*"
```

Configuration app
----------

```php

'components' =>
[
    'i18n' => [
        'translations' =>
        [
            'skeeks/logdb/app' => [
                'class'             => 'yii\i18n\PhpMessageSource',
                'basePath'          => '@skeeks/cms/logDbTarget/messages',
                'fileMap' => [
                    'skeeks/logdb/app' => 'app.php',
                ],
            ]
        ]
    ],

    'logDbTargetSettings' => [
        'class'     => 'skeeks\cms\logDbTarget\components\LogDbTargetSettings',
    ],

    'log' => [
        'targets' => [
            [
                'class'     => 'skeeks\cms\logDbTarget\LogDbTarget',
            ],
        ],
    ]
],

'modules' =>
[
    'logDbTarget' => [
        'class'         => '\skeeks\cms\logDbTarget\Module',
    ]
]

```

Links
-----
* [Web site](https://cms.skeeks.com)
* [Author](https://skeeks.com)
* [ChangeLog](https://github.com/skeeks-cms/cms-log-db-target/blob/master/CHANGELOG.md)


___

> [![skeeks!](https://skeeks.com/img/logo/logo-no-title-80px.png)](https://skeeks.com)  
<i>SkeekS CMS (Yii2) — quickly, easily and effectively!</i>  
[skeeks.com](https://skeeks.com) | [cms.skeeks.com](https://cms.skeeks.com)

