<?php

namespace common\modules\restapi\v1\controllers;

use common\modules\restapi\v1\controllers\DefaultRestController;

/**
 * Insurance controller for the `restapiv1` module
 */
class DocumentController extends DefaultRestController
{
	public $modelClass = 'common\modules\restapi\v1\models\Document';
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
				//~ [
					//~ 'allow' => true,
					//~ 'roles' => ['@'],
				//~ ],
				[
					'allow' => true,
					'verbs' => ['GET', 'HEAD', 'OPTIONS'],
					'roles' => ['createDocument', 'updateDocument'],
				],
				[
					'allow' => true,
					'verbs' => ['POST'],
					'roles' => ['createDocument'],
				],
				[
					'allow' => true,
					'verbs' => ['PATCH', 'DELETE'],
					'roles' => ['updateDocument'],
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
			'query' => \common\modules\restapi\v1\models\Document::find(),
			'pagination' => [
				'defaultPageSize' => \yii::$app->params['pageSize'],
			],
		]);
		
		return $dataProvider;
	}
	
	/**
	 * Search Documents by fields
	 * @return \common\modules\restapi\v1\models\Document[]
	 */
	public function actionSearch() {
		$post = \Yii::$app->request->post();
		$query = \common\modules\restapi\v1\models\Document::find();
		
		if (isset($post['document_type']))
			$query = $query->andWhere(['document_type' => $post['document_type']]);
		if (isset($post['serial']))
			$query = $query->andWhere(['ilike', 'serial', $post['serial']]);
		if (isset($post['number']))
			$query = $query->andWhere(['number' => $post['number']]);
		if (isset($post['surname']))
			$query = $query->andWhere(['ilike', 'surname', $post['surname']]);
		if (isset($post['name']))
			$query = $query->andWhere(['ilike', 'name', $post['name']]);
		if (isset($post['secondname']))
			$query = $query->andWhere(['ilike', 'secondname', $post['secondname']]);
		if (isset($post['birthdate']))
			$query = $query->andWhere(['birthdate' => $post['birthdate']]);
		if (isset($post['person_id']))
			$query = $query->andWhere(['person_id' => $post['person_id']]);
		if (isset($post['issue_date']))
			$query = $query->andWhere(['issue_date' => $post['issue_date']]);
		if (isset($post['issuer']))
			$query = $query->andWhere(['ilike', 'issuer', $post['issuer']]);
		if (isset($post['expire_date']))
			$query = $query->andWhere(['expire_date' => $post['expire_date']]);
		
		$dataProvider = new \yii\data\ActiveDataProvider([
			'query' => $query,
		]);
		
		return $dataProvider;
	}
}
