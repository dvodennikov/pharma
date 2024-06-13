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
    'on ' . yii\web\Application::EVENT_BEFORE_REQUEST => function() {
		$request  = \Yii::$app->getRequest();
		$language = $request->get('language', null);
		$cookies  = $request->getCookies();
		
		if (isset($language)) {
			//$cookies->add(new \yii\web\Cookie(['name' => 'language', 'value' => $language]));
			\Yii::$app->getResponse()->getCookies()->add(new \yii\web\Cookie(['name' => 'language', 'value' => mb_substr($language, 0, 5)]));
			\Yii::$app->language = mb_substr($language, 0, 5);
		} elseif ($cookies->has('language')) {
			\Yii::$app->language = $cookies->getValue('language');
		}
	},
    'name' => 'Pharma',
    'language' => 'ru-RU',
];
