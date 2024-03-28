<?php

namespace backend\controllers;

use common\models\DocumentType;
use common\models\DocumentTypeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DocumentTypeController implements the CRUD actions for DocumentType model.
 */
class DocumentTypeController extends Controller
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
     * Lists all DocumentType models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new DocumentTypeSearch();
        
        /*if (is_array($searchModel->custom_fields) && isset($searchModel->custom_fields[0])) {
			$searchModel->custom_fields = $searchModel->custom_fields[0];
		} else {
			$searchModel->custom_fields = '';
		}
			
		$searchModel->custom_fields_text = $searchModel->custom_fields;*/
			
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DocumentType model.
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
     * Creates a new DocumentType model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model        = new DocumentType();
        $customFields = DocumentType::parseCustomFields(\Yii::$app->request->getBodyParam('customFields'));
        
        if ($this->request->isPost) {
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
        } else {
            $model->loadDefaultValues();
        }
        
        return $this->render('create', [
            'model' => $model,
            'customFields' => $customFields,
        ]);
    }

    /**
     * Updates an existing DocumentType model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model        = $this->findModel($id);
        $customFields = DocumentType::parseCustomFields(\Yii::$app->request->getBodyParam('customFields'));
        $model->customFields = $customFields;
        
        if ($this->request->isPost && $model->load($this->request->post())) {
			$custom_fields = [];
			foreach ($customFields as $customField) {
				$custom_fields[] = (object) $customField;
			}
			
			$model->custom_fields = $custom_fields;
			
			if ($model->save()) {
				return $this->redirect(['view', 'id' => $model->id]);
			}
		}
		
		if (count($customFields) <= 0)
			$customFields = DocumentType::parseCustomFields($model->custom_fields);

        return $this->render('update', [
            'model' => $model,
            'customFields' => $customFields,
        ]);
    }

    /**
     * Deletes an existing DocumentType model.
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
     * Add custom field to custom fields list of DocumentType model.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionAddCustomFields($id)
    {
		$model        = $id?$this->findModel($id):new DocumentType();
		$customFields = DocumentType::parseCustomFields(\Yii::$app->request->getBodyParam('customFields'));
		
		$model->load($this->request->post());
		
		$customFields[] = ['title' => '', 'mask' => ''];
		$model->custom_fields = $customFields;
		
		return $this->render($id?'update':'create', [
			'model' => $model,
		]);
	}
	
	/**
     * Add custom field to custom fields list of DocumentType model.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
     public function actionDeleteCustomField($id)
     {
		 $model        = $id?$this->findModel($id):new DocumentType();
		 $idx          = (int) \Yii::$app->request->get('idx', -1);
		 $customFields = DocumentType::parseCustomFields(\Yii::$app->request->getBodyParam('customFields'));
		
		$model->load($this->request->post());
		
		if (array_key_exists($idx, $customFields)) {
			array_splice($customFields, $idx, 1);
		}
		
		$model->custom_fields = $customFields;
		
		return $this->render($id?'update':'create', [
			'model' => $model,
		]);
	 }
	
	/**
	 * Return HTML for custom fields group for AJAX request
	 * @return string|\yii\web\Response
	 */
	 public function actionGetCustomFields()
	 {
		 $idx = (int) \Yii::$app->request->get('idx', 0);
		 
		 return $this->renderAjax('_custom_fields', [
			'customField' => [],
			'idx'         => $idx,
		 ]);
	 }

    /**
     * Finds the DocumentType model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return DocumentType the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DocumentType::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
