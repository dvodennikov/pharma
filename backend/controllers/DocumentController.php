<?php

namespace backend\controllers;

use common\models\Document;
use common\models\DocumentSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
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
				'access' => [
	                'class' => AccessControl::class,
	                'rules' => [
	                    /*[
	                        'allow' => true,
	                        'roles' => ['@'],
	                    ],*/
	                    [
							'allow' => true,
							'actions' => ['index', 'view'],
							'roles' => ['createDocument', 'updateDocument'],
						],
						[
							'allow' => true,
							'actions' => [
								'create', 
								'refresh', 
								'get-custom-fields', 
								'get-persons'
							],
							'roles' => ['createDocument'],
						],
						[
							'allow' => true,
							'actions' => [
								'update', 
								'delete',
								'refresh', 
								'get-custom-fields', 
								'get-persons'
								],
							'roles' => ['updateDocument'],
						],
	                ],
	            ],
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
        
        if (isset(\Yii::$app->params['pageSize']))
			$dataProvider->pagination->pageSize = (int) \Yii::$app->params['pageSize'];

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
				//$customFields        = Document::parseCustomFields(\Yii::$app->request->getBodyParam('customFields'), $model->document_type, $model);
				//$customFields        = \Yii::$app->request->getBodyParam('customFields');
				//$model->customFields = $customFields;
				
				/*$custom_fields = [];
				foreach ($customFields as $customField) {
					$custom_fields[] = (object) $customField;
				}
				
				$model->custom_fields = $custom_fields;*/
				//$model->custom_fields = $customFields;
				//$model->validate('custom_fields');
				Document::parseCustomFields($model->custom_fields, $model->document_type, $model);
				
				if (!$model->hasErrors() && $model->save()) {
					if ((count($model->custom_fields) == 0) && (\common\models\DocumentType::getCustomFieldsCount($model->document_type) > 0)) {
						return $this->redirect(['update', 'id' => $model->id]);
					}
					
					return $this->redirect(['view', 'id' => $model->id]);
				}
            }
        } else {
            $model->loadDefaultValues();
            
            $person_id = \Yii::$app->request->get('person_id', null);
            if (!is_null($person_id)) {
				$person = \common\models\Person::findOne(['id' => $person_id]);
				
				if (isset($person->id)) {
					$model->person_id  = $person->id;
					$model->surname    = $person->surname;
					$model->name       = $person->name;
					$model->secondname = $person->secondname;
					$model->birthdate  = $person->birthdate;
				}
			}
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
			//$customFields        = Document::parseCustomFields(\Yii::$app->request->getBodyParam('customFields'), $model->document_type, $model);
			//$model->customFields = $customFields;
			//throw new \yii\base\NotSupportedException(print_r($customFields, 1));
            if ($model->load($this->request->post())) {
				/*$custom_fields = [];
				foreach ($customFields as $customField) {
					$custom_fields[] = (object) $customField;
				}
				
				$model->custom_fields = $custom_fields;*/
				//$model->custom_fields = $customFields;
				
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
		
		/*$customFields = Document::parseCustomFields(\Yii::$app->request->getBodyParam('customFields'), 
													$model->document_type,
													$model);*/
		
		//$model->custom_fields = $customFields;
		
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
	 * Return HTML for custom fields for DocumentType model by id
	 * for AJAX request
	 * @return string|\yii\web\Response
	 */
	public function actionGetCustomFields($id)
	{
		$idx = (int) \Yii::$app->request->get('idx', 0);
		$documentType = \common\models\DocumentType::findOne(['id' => $id]);

		$response = '';
		foreach ($documentType->custom_fields as $customField) {
			$response .= $this->renderAjax('_custom_fields', [
				'customField' => $customField,
				'fieldParams' => $customField,
				'idx'         => $idx++,
			]);
		}
		
		return $response;
	 }

    /**
	 * Return HTML for person list for Person model by surname, name, 
	 * secondname and birthdate for AJAX request
	 * @return string|\yii\web\Response
	 */
	public function actionGetPersons($id)
	{
		$person     = [];
		$person['surname']    = \Yii::$app->request->get('surname', null);
		$person['name']       = \Yii::$app->request->get('name', null);
		$person['secondname'] = \Yii::$app->request->get('secondname', null);
		$person['birthdate']  = \Yii::$app->request->get('birthdate', null);
		
		$model = $this->findModel($id);
		
		if (!isset($model->id))
			throw new NotFoundHttpException(\Yii::t('app', 'Not found'));
		
		foreach ($person as $name => $field) {
			//if (isset($field))
				$model->$name = $field;
		}

		return $this->renderAjax('_person', ['model' => $model]);
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

        throw new NotFoundHttpException(\Yii::t('app', 'The requested page does not exist.'));
    }
}
