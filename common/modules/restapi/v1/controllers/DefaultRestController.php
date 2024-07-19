<?php
/*
 * DefaultRestController.php
 */

namespace common\modules\restapi\v1\controllers;

use yii\rest\ActiveController;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\Cors;

/**
 * Default REST controller for the `restapiv1` module
 */
class DefaultRestController extends ActiveController
{
	public $modelClass = 'common\modules\restapi\v1\models\Login';
	
	public function behaviors()
	{
		$behaviors = parent::behaviors();
		
		// remove authentication filter
		//$auth = $behaviors['authenticator'];
		unset($behaviors['authenticator']);
		
		// add CORS filter
		$behaviors['corsFilter'] = [
			'class' => Cors::class,
			'cors' => [
				'Origin' => ['*'],
				'Access-Control-Request-Method' => ['GET','POST','PATCH','PUT','DELETE', 'OPTIONS'],
				'Access-Control-Allow-Headers' => ['content-type', 'authorization', 'X-Requested-With'],
				//'Access-Control-Allow-Origin' => ['*'],
				'Access-Control-Request-Headers' => ['*'],
			],
		];
			
        // re-add authentication filter
		$behaviors['authenticator'] = [
			'class' => CompositeAuth::class,
			'authMethods' => [
				HttpBasicAuth::class,
				HttpBearerAuth::class,
			]
		];
		
		$behaviors['authenticator']['except'] = ['options', 'login', 'signup'];
		
		$behaviors['contentNegotiator'] = [
			'class' => \yii\filters\ContentNegotiator::class,
			'formatParam' => '_format',
			'formats' => [
				'application/json' => \yii\web\Response::FORMAT_JSON,
				'application/xml' => \yii\web\Response::FORMAT_XML,
			],
		];
		
		return $behaviors;
	}
	
}
