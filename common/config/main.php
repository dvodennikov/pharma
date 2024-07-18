<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => \yii\caching\FileCache::class,
        ],
        'authManager' => [
			'class' => \yii\rbac\DbManager::class,
        ],
        'assetManager' => [
			'appendTimestamp' => true,
        ],
        'i18n' => [
			'translations' => [
				'app*' => [
					'class'          => 'yii\i18n\PhpMessageSource',
					'basePath'       => dirname(dirname(__DIR__)) . '/common/messages',
					'sourceLanguage' => 'en-US',
					'fileMap'        => [
						'app' => 'app.php',
					],
				],
			],
		],
    ],
    'name' => 'Pharma',
    'language' => 'ru-RU',
];
