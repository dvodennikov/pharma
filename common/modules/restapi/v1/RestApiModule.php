<?php

namespace common\modules\restapi\v1;

/**
 * RestApiModule v1 definition class
 */
class RestApiModule extends \yii\base\Module 
{
	
	/**
	 * @{inheritdoc}
	 */
	public $controllerNamespace = 'common\modules\restapi\v1\controllers';
	
	/**
	 * @{inheritdoc}
	 */
	public function init() 
	{
		parent::init();
		
		\Yii::$app->user->enableSession = false;
	}
	
	/**
	 * @{inheritdoc}
	 */
	public function behaviors()
	{
		$behaviors = parent::behaviors();
		
		$behaviors['contentNegotiator'] = [
			'class'       => \yii\filters\ContentNegotiator::class,
			'formatParam' => '_format',
			'formats'     => [
				'application/json' => \Yii\web\Response::FORMAT_JSON,
				'application/xml'  => \Yii\web\Response::FORMAT_XML,
			],
		];
		
		return $behaviors;
	}
}
