<?php

namespace backend\controllers;

use common\models\Document;
use common\models\DocumentSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DocumentController implements the CRUD actions for Document model.
 */
class DocumentController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Document models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new DocumentSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Document model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Document model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Document();
        
        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
				$customFields = Document::parseCustomFields(\Yii::$app->request->getBodyParam('customFields'), $model->document_type, $model);
				//throw new \yii\base\NotSupportedException(print_r($customFields, true));
				$model->customFields = $customFields;
				$custom_fields = [];
				foreach ($customFields as $customField) {
					$custom_fields[] = (object) $customField;
				}
				
				$model->custom_fields = $custom_fields;
				
				if ($model->save()) {
					if ((count($model->custom_fields) == 0) && (\common\models\DocumentType::getCustomFieldsCount($model->document_type) > 0)) {
						//throw new \yii\base\NotSupportedException(print_r($model, true));
						return $this->redirect(['update', 'id' => $model->id]);
					}
					
					return $this->redirect(['view', 'id' => $model->id]);
				}
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Document model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        
        if ($this->request->isPost) {
			$customFields        = Document::parseCustomFields(\Yii::$app->request->getBodyParam('customFields'), $model->document_type, $model);
			$model->customFields = $customFields;
			//throw new \yii\base\NotSupportedException(print_r($customFields, 1));
            if ($model->load($this->request->post())) {
				$custom_fields = [];
				foreach ($customFields as $customField) {
					$custom_fields[] = (object) $customField;
				}
				
				$model->custom_fields = $custom_fields;
				
				if ($model->save()) {
					return $this->redirect(['view', 'id' => $model->id]);
				}
            }
        }
        
        return $this->render('update', [
            'model' => $model,
        ]);
    }
    
    /**
     * Refresh Document model.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionRefresh($id)
    {
		$model = $id?$this->findModel($id):new Document();
		
		$model->load($this->request->post());
		
		$customFields = Document::parseCustomFields(\Yii::$app->request->getBodyParam('customFields'), 
													$model->document_type);
		
		$model->custom_fields = $customFields;
		
		return $this->render($id?'update':'create', [
			'model' => $model,
		]);
	}

    /**
     * Deletes an existing Document model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Document model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Document the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        //if (($model = Document::findOne(['id' => $id])) !== null) {
        if (($model = Document::find()->joinWith('documentType')->andWhere([Document::tableName() . '.id' => $id])->one()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
