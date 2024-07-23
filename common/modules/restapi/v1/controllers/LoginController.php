<?php

namespace common\modules\restapi\v1\controllers;

use common\modules\restapi\v1\controllers\DefaultRestController;
use common\modules\restapi\v1\models\Login;
use \yii\filters\AccessControl;

/**
 * Default controller for the `restapiv1` module
 */
class LoginController extends DefaultRestController
{
	public $modelClass = 'common\modules\restapi\v1\models\Login';
	
	/**
	 * {@inheritdoc}
	 */
	public function behaviors()
	{
		$behaviors = parent::behaviors();
		$behaviors['access'] = [
			'class' => \yii\filters\AccessControl::class,
			'rules' => [
				[
					'actions' => ['info', 'login'],
					'allow'   => true,
				],
				[
					'actions' => ['logout'],
					'allow'   => true,
					'roles'   => ['@'],
				],
			],
		];
		
		return $behaviors;
	}
	
	/**
	 * Return API's name and version
	 */
    public function actionInfo()
    {
		return ['name' => 'Rest Api v1', 'version' => '1.0'];
	}
    
    public function actionLogin() 
	{
		$model = new Login();
		$model->load(\Yii::$app->request->post(), '');

		return $model->login();
	}
	
	
	public function actionLogout() 
	{
		if ($user = \Yii::$app->user->identity) {
			$user->removeAccessToken();
			$user->save();
			\Yii::$app->user->logout();
			
			return [
				'success' => true,
				'status'  => 200
			];
		}
		
		//\Yii::$app->response->statusCode = 204;
		
		return [
			'success' => false,
			'status'  => 401
		];
	}
}
