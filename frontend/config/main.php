<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
            'parsers' => [
				'application/json' => 'yii\web\JsonParser'
			],
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => \yii\log\FileTarget::class,
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false,
            'rules' => [
				[
					'class' => 'yii\rest\UrlRule',
					'controller' => ['login' => 'restapiv1/'],
					'pluralize' => false,
				],
				[
					'class' => 'yii\rest\UrlRule',
					'controller' => ['restapiv1/document', 'restapiv1/document'],
					'pluralize' => false,
					'extraPatterns' => [
						'GET find' => 'search',
					],
				],
				[
					'class' => 'yii\rest\UrlRule',
					'controller' => ['restapiv1/document-type', 'restapiv1/document-type'],
					'pluralize' => false,
					'extraPatterns' => [
						'GET search' => 'search',
					],
				],
				[
					'class' => 'yii\rest\UrlRule',
					'controller' => ['restapiv1/person', 'restapiv1/person'],
					'pluralize' => false,
					'extraPatterns' => [
						'GET search' => 'search',
					],
				],
				[
					'class' => 'yii\rest\UrlRule',
					'controller' => ['restapiv1/unit', 'restapiv1/unit'],
					'pluralize' => false,
					'extraPatterns' => [
						'GET search' => 'search',
					],
				],
				[
					'class' => 'yii\rest\UrlRule',
					'controller' => ['restapiv1/drug', 'restapiv1/drug'],
					'pluralize' => false,
					'extraPatterns' => [
						'GET search' => 'search',
					],
				],
				[
					'class' => 'yii\rest\UrlRule',
					'controller' => ['restapiv1/receipt', 'restapiv1/receipt'],
					'pluralize' => false,
					'extraPatterns' => [
						'GET search' => 'search',
					],
				],
            ],
        ],
    ],
    'modules' => [
		'restapiv1' => [
			'class' => 'common\modules\restapi\v1\RestApiModule',
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
    'params' => $params,
];
