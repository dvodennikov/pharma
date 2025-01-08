<?php

namespace common\modules\restapi\v1\controllers;

use common\modules\restapi\v1\controllers\DefaultRestController;
use common\modules\restapi\v1\models\Receipt;
use \yii\web\NotFoundHttpException;
use \yii\web\ForbiddenHttpException;
use \yii\web\BadRequestHttpException;
use Yii;

/**
 * Receipt controller for the `restapiv1` module
 */
class ReceiptController extends DefaultRestController
{
	public $modelClass = 'common\modules\restapi\v1\models\Receipt';
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
					'roles' => ['createReceipt', 'updateReceipt'],
				],
				[
					'allow' => true,
					'verbs' => ['POST'],
					'roles' => ['createReceipt'],
				],
				[
					'allow' => true,
					'verbs' => ['PATCH', 'PUT', 'DELETE'],
					'roles' => ['updateReceipt'],
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
		unset($actions['create']);
		unset($actions['update']);
		
		return $actions;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function actionIndex() {
		$dataProvider = new \yii\data\ActiveDataProvider([
			'query' => \common\modules\restapi\v1\models\Receipt::find(),
			'pagination' => [
				'defaultPageSize' => \yii::$app->params['pageSize'],
			],
		]);
		
		return $dataProvider;
	}
	
	/**
     * Creates a new Receipt model.
     * @return string|\yii\web\Response
     * @throws ForbiddenHttpException if not used POST method
     * @throws BadRequestHttpException if could't parse/validate data
     */
    public function actionCreate()
    {
        $model = new Receipt();

        if ($this->request->isPost) {
			if (!$model->load($this->request->post(), ''))
				throw new BadRequestHttpException(
					Yii::t('restapi', 'Could not load data: {error}', 
						['error' => $model->hasErrors()?implode(' ', $model->getErrorSummary()):Yii::t('restapi', 'Could not parse data')])
				);
			
			if (!isset($this->request->post()['receiptDrugs']))
				throw new BadRequestHttpException(Yii::t('app', 'No drugs specified'));
			
			$model->updateReceiptWithReceiptDrugs(false, $this->request->post()['receiptDrugs']);
				
			return $model;
		} else {
			throw new ForbiddenHttpException(Yii::t('restapi', 'POST request must be used to create receipt'));
		}
    }

    /**
     * Updates an existing Receipt model.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws ForbiddenHttpException if not used PUT/PATCH method
     * @throws BadRequestHttpException if could't parse/validate data
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if (($this->request->isPut || $this->request->isPatch)) {
			if (!$model->load($this->request->post(), ''))
				throw new BadRequestHttpException(
					Yii::t('restapi', 'Could not load data: {error}', 
						['error' => $model->hasErrors()?implode(' ', $model->getErrorSummary()):Yii::t('restapi', 'Could not parse data')])
				);
		
			if (!isset($this->request->post()['receiptDrugs']))
				throw new BadRequestHttpException(Yii::t('app', 'No drugs specified'));
			
			$model->updateReceiptWithReceiptDrugs(true, $this->request->post()['receiptDrugs']);
			
			return $model;
		} else {
			throw new ForbiddenHttpException(Yii::t('restapi', 'PUT/PATCH request must be used to update receipt'));
        }
    }
	
	/**
	 * Search Receipts by fields
	 * @return \common\modules\restapi\v1\models\Receipt[]
	 */
	public function actionSearch() {
		$post = \Yii::$app->request->post();
		$query = \common\modules\restapi\v1\models\Receipt::find();
		
		if (isset($post['number']))
			$query = $query->andWhere(['number' => $post['number']]);
		if (isset($post['person_id']))
			$query = $query->andWhere(['person_id' => $post['person_id']]);
		if (isset($post['issue_date']))
			$query = $query->andWhere(['issue_date' => $post['issue_date']]);
		if (isset($post['sell_date']))
			$query = $query->andWhere(['sell_date' => $post['sell_date']]);
		
		$dataProvider = new \yii\data\ActiveDataProvider([
			'query' => $query,
		]);
		
		return $dataProvider;
	}
	
	/**
     * Finds the Receipt model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Receipt the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Receipt::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
