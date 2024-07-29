<?php

namespace common\modules\restapi\v1\controllers;

use common\modules\restapi\v1\controllers\DefaultRestController;

/**
 * Insurance controller for the `restapiv1` module
 */
class PersonController extends DefaultRestController
{
	public $modelClass = 'common\modules\restapi\v1\models\Person';
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
					'roles' => ['createPerson', 'updatePerson'],
				],
				[
					'allow' => true,
					'verbs' => ['POST'],
					'roles' => ['createPerson'],
				],
				[
					'allow' => true,
					'verbs' => ['PATCH', 'DELETE'],
					'roles' => ['updatePerson'],
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
			'query' => \common\modules\restapi\v1\models\Person::find(),
			'pagination' => [
				'defaultPageSize' => \yii::$app->params['pageSize'],
			],
		]);
		
		return $dataProvider;
	}
	
	/**
	 * Search Persons by fields
	 * @return \common\modules\restapi\v1\models\Person[]
	 */
	public function actionSearch() {
		$post = \Yii::$app->request->post();
		$query = \common\modules\restapi\v1\models\Person::find();
		
		if (isset($post['surname']))
			$query = $query->andWhere(['ilike', 'surname', $post['surname']]);
		if (isset($post['name']))
			$query = $query->andWhere(['ilike', 'name', $post['name']]);
		if (isset($post['secondname']))
			$query = $query->andWhere(['ilike', 'secondname', $post['secondname']]);
		if (isset($post['birthdate']))
			$query = $query->andWhere(['birthdate' => $post['birthdate']]);
		if (isset($post['address']))
			$query = $query->andWhere(['ilike', 'address', $post['address']]);
		if (isset($post['snils']))
			$query = $query->andWhere(['ilike', 'snils', $post['snils']]);
		if (isset($post['polis']))
			$query = $query->andWhere(['ilike', 'polis', $post['polis']]);
		
		$dataProvider = new \yii\data\ActiveDataProvider([
			'query' => $query,
		]);
		
		return $dataProvider;
	}
}
