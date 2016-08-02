<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
	'language' => 'th',
    'sourceLanguage' => 'en',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
	    'i18n' => [
	        'translations' => [
	            'app*' => [
	                'class' => 'yii\i18n\PhpMessageSource',
	                'basePath' => '@common/messages',
	                'fileMap' => [
	                    'app' => 'app.php',
	                    'app/error' => 'error.php',
	                ],
	            ],
	        ],
	    ],
    ],
    'timeZone' => 'Asia/Bangkok',
];
