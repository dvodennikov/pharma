<?php

namespace common\modules\restapi\v1\controllers;

use common\modules\restapi\v1\controllers\DefaultRestController;

/**
 * Insurance controller for the `restapiv1` module
 */
class DocumentTypeController extends DefaultRestController
{
	public $modelClass = 'common\modules\restapi\v1\models\DocumentType';
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
					'roles' => ['createDocumentType', 'updateDocumentType'],
				],
				[
					'allow' => true,
					'verbs' => ['POST'],
					'roles' => ['createDocumentType'],
				],
				[
					'allow' => true,
					'verbs' => ['PATCH', 'PUT', 'DELETE'],
					'roles' => ['updateDocumentType'],
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
			'query' => \common\modules\restapi\v1\models\DocumentType::find(),
			'pagination' => [
				'defaultPageSize' => \yii::$app->params['pageSize'],
			],
		]);
		
		return $dataProvider;
	}
	
	/**
	 * Search DocumentTypes by fields
	 * @return \common\modules\restapi\v1\models\DocumentType[]
	 */
	public function actionSearch() {
		$post = \Yii::$app->request->post();
		$query = \common\modules\restapi\v1\models\DocumentType::find();
		
		if (isset($post['title']))
			$query = $query->andWhere(['title' => $post['title']]);
		if (isset($post['serial_mask']))
			$query = $query->andWhere(['ilike', 'serial_mask', $post['serial_mask']]);
		if (isset($post['number_mask']))
			$query = $query->andWhere(['ilike', 'number_mask', $post['number_mask']]);
		
		$dataProvider = new \yii\data\ActiveDataProvider([
			'query' => $query,
		]);
		
		return $dataProvider;
	}
}
