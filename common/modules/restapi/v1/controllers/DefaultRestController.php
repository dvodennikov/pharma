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
use Yii;

/**
 * Default REST controller for the `restapiv1` module
 */
class DefaultRestController extends ActiveController
{
	/**
	 * {@inheritdoc}
	 */
	public function behaviors()
	{
		$behaviors = parent::behaviors();
		
		// remove authentication filter
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
	
	/**
	 * {@inheritdoc}
	 */
	public function beforeAction($action)
	{
		$sessionDuration = 3600;
		
		if (isset(Yii::$app->params['restapi.v1.sessionDuration'])) {
			$sessionDuration = (int)Yii::$app->params['restapi.v1.sessionDuration']; 
		} elseif (isset(Yii::$app->params['restapi.sessionDuration'])) {
			$sessionDuration = (int)Yii::$app->params['restapi.sessionDuration'];
		}
			
		$authorizationHeader = Yii::$app->request->getHeaders()->get('Authorization');
		
		if (preg_match('/Bearer\s+(\\S+)$/i', $authorizationHeader, $matches)) {
			$user = \common\models\User::findIdentityByAccessToken($matches[1]);
			
			if (isset($user) && $user->isAccessTokenExpire(true, $sessionDuration))
				throw new \yii\web\UnauthorizedHttpException();
		}
		
		if (!parent::beforeAction($action)) 
			return false;
			
		return true;
	}
}
