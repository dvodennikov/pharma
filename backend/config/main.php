<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
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
            'rules' => [
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
    'params' => $params,
];
