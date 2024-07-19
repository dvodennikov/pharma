<?php

namespace common\modules\restapi\v1\controllers;

use common\modules\restapi\v1\controllers\DefaultRestController;
use common\modules\restapi\v1\models\Login;
use \yii\filters\AccessControl;

/**
 * Default controller for the `restapiv1` module
 */
class DefaultController extends DefaultRestController
{
	/**
	 * {@inheritdoc}
	 */
	public function behaviors()
	{
		return [
			'access' => [
				'class' => \yii\filters\AccessControl::class,
				'rules' => [
					[
						'actions' => ['info', 'login', 'logout'],
						'allow'   => true,
					],
				],
			],
		];
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
		$model = new Login();
		
		return $model->logout();
		
	}
}
