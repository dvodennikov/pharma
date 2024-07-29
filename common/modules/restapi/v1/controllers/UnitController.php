<?php

namespace common\modules\restapi\v1\controllers;

use common\modules\restapi\v1\controllers\DefaultRestController;

/**
 * Insurance controller for the `restapiv1` module
 */
class UnitController extends DefaultRestController
{
	public $modelClass = 'common\modules\restapi\v1\models\Unit';
	public $serializer = [
		'class' => 'yii\rest\Serializer',
		'collectionEnvelope' => 'items'
	];
	
	/**
	 * {@inheritdoc}
	 */
	public function behaviors() {
		$behaviors = parent::behaviors();
		$behaviors['access'] = [
			'class' => 'yii\filters\AccessControl',
			'rules' => [
				[
					'allow' => true,
					'verbs' => ['GET', 'HEAD', 'OPTIONS'],
					'roles' => ['createUnit', 'updateUnit'],
				],
				[
					'allow' => true,
					'verbs' => ['POST'],
					'roles' => ['createUnit'],
				],
				[
					'allow' => true,
					'verbs' => ['PATCH', 'DELETE'],
					'roles' => ['updateUnit'],
				],
			],
		];
		
		return $behaviors;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function actions() {
		$actions = parent::actions();
		unset($actions['index']);
		
		return $actions;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function actionIndex() {
		$dataProvider = new \yii\data\ActiveDataProvider([
			'query' => \common\modules\restapi\v1\models\Unit::find(),
			'pagination' => [
				'defaultPageSize' => \yii::$app->params['pageSize'],
			],
		]);
		
		return $dataProvider;
	}
	
	/**
	 * Search Units by fields
	 * @return \common\modules\restapi\v1\models\Unit[]
	 */
	public function actionSearch() {
		$post = \Yii::$app->request->post();
		$query = \common\modules\restapi\v1\models\Unit::find();
		
		if (isset($post['title']))
			$query = $query->andWhere(['ilike', 'title', $post['title']]);
		if (isset($post['description']))
			$query = $query->andWhere(['ilike', 'description', $post['description']]);
		
		$dataProvider = new \yii\data\ActiveDataProvider([
			'query' => $query,
		]);
		
		return $dataProvider;
	}
}
