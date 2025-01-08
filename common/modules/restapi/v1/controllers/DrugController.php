<?php

namespace common\modules\restapi\v1\controllers;

use common\modules\restapi\v1\controllers\DefaultRestController;

/**
 * Insurance controller for the `restapiv1` module
 */
class DrugController extends DefaultRestController
{
	public $modelClass = 'common\modules\restapi\v1\models\Drug';
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
					'roles' => ['createDrug', 'updateDrug'],
				],
				[
					'allow' => true,
					'verbs' => ['POST'],
					'roles' => ['createDrug'],
				],
				[
					'allow' => true,
					'verbs' => ['PATCH', 'PUT', 'DELETE'],
					'roles' => ['updateDrug'],
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
			'query' => \common\modules\restapi\v1\models\Drug::find(),
			'pagination' => [
				'defaultPageSize' => \yii::$app->params['pageSize'],
			],
		]);
		
		return $dataProvider;
	}
	
	/**
	 * Search Drugs by fields
	 * @return \common\modules\restapi\v1\models\Drug[]
	 */
	public function actionSearch() {
		$post = \Yii::$app->request->post();
		$query = \common\modules\restapi\v1\models\Drug::find();
		
		if (isset($post['title']))
			$query = $query->andWhere(['ilike', 'title', $post['title']]);
		if (isset($post['description']))
			$query = $query->andWhere(['ilike', 'description', $post['description']]);
		if (isset($post['measury']))
			$query = $query->andWhere(['measury' => $post['measury']]);
		if (isset($post['measury_unit']))
			$query = $query->andWhere(['measury_unit' => $post['measury_unit']]);
		
		$dataProvider = new \yii\data\ActiveDataProvider([
			'query' => $query,
		]);
		
		return $dataProvider;
	}
}
