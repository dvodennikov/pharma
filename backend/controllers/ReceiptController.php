<?php

namespace backend\controllers;

use common\models\Receipt;
use common\models\ReceiptSearch;
use common\models\ReceiptDrugs;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

/**
 * ReceiptController implements the CRUD actions for Receipt model.
 */
class ReceiptController extends Controller
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
							'roles' => ['createReceipt', 'updateReceipt'],
						],
						[
							'allow' => true,
							'actions' => [
								'create',
								'search-person',
								'get-persons',
								'add-drug',
								'get-new-drug',
								'delete-drug',
								'search-drug',
								'get-search-drugs'
							],
							'roles' => ['createReceipt'],
						],
						[
							'allow' => true,
							'actions' => [
								'update', 
								'delete',
								'search-person',
								'get-persons',
								'add-drug',
								'get-new-drug',
								'delete-drug',
								'search-drug',
								'get-search-drugs'
							],
							'roles' => ['updateReceipt'],
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
     * Lists all Receipt models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ReceiptSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Receipt model.
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
     * Creates a new Receipt model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Receipt();

        if ($this->request->isPost) {
			$receiptDrugs      = ReceiptDrugs::loadReceiptDrugs('ReceiptDrugs', $model);
			
			foreach ($receiptDrugs as $receiptDrug)
				if ($receiptDrug->hasErrors()) {
					$errors = $receiptDrug->getFirstErrors();
					$model->addError('drugs', array_shift($errors));
					
					break;
				}
			//throw new \yii\base\NotSupportedException(print_r($receiptDrugs, true));
					
            if ($model->load($this->request->post()) && !$model->hasErrors() && $model->save()) {
				foreach ($receiptDrugs as &$receiptDrug) {
					$receiptDrug->receipt_id = $model->getPrimaryKey();
					//throw new \yii\base\NotSupportedException(print_r($receiptDrug, true));
					$receiptDrug->save();
				}
				
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model'        => $model,
            'receiptDrugs' => [],
        ]);
    }

    /**
     * Updates an existing Receipt model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post())) {
			$receiptDrugs      = ReceiptDrugs::loadReceiptDrugs('ReceiptDrugs', $model);
			//throw new \yii\base\NotSupportedException(print_r($receiptDrugs, true));
			
			foreach ($receiptDrugs as $receiptDrug)
				if ($receiptDrug->hasErrors()) {
					$errors = $receiptDrug->getFirstErrors();
					$model->addError('drugs', array_shift($errors));
					
					return $this->render('update', [
						'model'        => $model,
						'receiptDrugs' => $receiptDrugs,
					]);
				}
			
			if (!$model->hasErrors() && $model->save()) {
				$receiptId      = $model->getPrimaryKey();
				$drugIds        = [];
				//$receiptDrugsDb = ReceiptDrugs::find()->where(['receipt_id' => $receiptId])->indexBy('id')->all();
				foreach ($receiptDrugs as &$receiptDrug) {
					/*if (isset($receiptDrug->id) && isset($receiptDrugsDb[$receiptDrug->id])) {
						$receiptDrugDb = &$receiptDrugsDb[$receiptDrug->id];
						$receiptDrugDb->quantity = $receiptDrug->quantity;
						
						$receiptDrugDb->save();
						
						$drugIds[] = $receiptDrugDb->id;
					} else {*/
						$receiptDrug->receipt_id = $receiptId;
						//throw new \yii\base\NotSupportedException(print_r($receiptDrug, true));
						$receiptDrug->save();
						
						$drugIds[] = $receiptDrug->getPrimaryKey();
					//}
				}
				
				ReceiptDrugs::deleteAll(['AND', 'receipt_id=:receipt_id', ['NOT', ['IN', 'id', $drugIds]]], ['receipt_id' => $receiptId]);
				
				return $this->redirect(['view', 'id' => $model->id]);
			}
        }

		$receiptDrugs = ReceiptDrugs::find()->where(['receipt_id' => $model->id])->with('drug')->all();

        return $this->render('update', [
            'model'        => $model,
            'receiptDrugs' => $receiptDrugs,
        ]);
    }

    /**
     * Deletes an existing Receipt model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        
        if (isset($model)) {
			$modelId = $model->id;
        
			if ($model->delete())
				ReceiptDrugs::deleteAll(['receipt_id' => $modelId]);
		}

        return $this->redirect(['index']);
    }
    
    /**
     * Search person for the form
     * @return string|\yii\web\Response
     */
    public function actionSearchPerson()
    {
		$id           = $this->request->get('id');
		$model        = isset($id)?$this->findModel((int) $id):new Receipt();
		$searchPerson = $this->request->post('person_search');
		$searchDrug   = $this->request->post('drug_search');
		
		if ($this->request->isPost && $model->load($this->request->post())) {
			return $this->render(isset($model->id)?'update':'create', [
				'model'        => $model,
				'receiptDrugs' => ReceiptDrugs::loadReceiptDrugs('ReceiptDrugs'),
				'searchPerson' => $searchPerson,
				'searchDrug'   => $searchDrug,
			]);
		}
		
		return $this->redirect([isset($id)?'update':'create']);
	}
	
	/**
	 * Get Persons list for AJAX
	 * @return string|\yii\webResponse
	 */
	public function actionGetPersons() 
	{
		$id           = $this->request->get('id');
		$model        = isset($id)?$this->findModel((int) $id):new Receipt();
		$searchPerson = $this->request->get('person_search');
		
		return $this->renderAjax('_person', [
			'model'        => $model,
			'searchPerson' => $searchPerson,
		]);
	}
	
	/**
	 * Add drug to ReceiptDrugs to the Receipt model
	 */
	public function actionAddDrug()
	{
		$id    = $this->request->get('id');
		$model = isset($id)?$this->findModel((int) $id):new Receipt();
		
		if ($this->request->isPost && $model->load($this->request->post())) {
			$receiptDrugs = ReceiptDrugs::loadReceiptDrugs('ReceiptDrugs');
			$newDrug      = ReceiptDrugs::loadReceiptDrug('AddDrug');
			$searchDrug   = $this->request->post('drug_search');
			$searchPerson = $this->request->post('person_search');
			
			if (!$newDrug->hasErrors()) 
				$receiptDrugs[] = $newDrug;

			return $this->render(isset($model->id)?'update':'create', [
				'model'        => $model,
				'receiptDrugs' => $receiptDrugs,
				'searchDrug'   => $searchDrug,
				'searchPerson' => $searchPerson,
			]);
		}
		
		return $this->redirect([isset($id)?'update':'create']);

	}
	
	/**
	 * Add drug to ReceiptDrugs to the Receipt model for AJAX
	 * @return string|\yii\webResponse
	 */
	public function actionGetNewDrug()
	{
		$receiptDrug = new ReceiptDrugs();
		$idx         = $this->request->get('idx', 0);
		$drugId      = $this->request->get('drug_id');
		$quantity    = $this->request->get('quantity', 1);

		if (isset($drugId)) {
			$drug = \common\models\Drug::find()->where(['id' => $drugId])->one();
		//throw new \yii\base\NotSupportedException(print_r($drug, true));
			
			if (isset($drug->id)) {
				$receiptDrug->drug_id            = $drug->id;
				$receiptDrug->quantity           = $quantity;
				$receiptDrug->drug->id           = $drug->id;
				$receiptDrug->drug->title        = $drug->title;
				$receiptDrug->drug->description  = $drug->description;
				$receiptDrug->drug->measury      = $drug->measury;
				$receiptDrug->drug->measury_unit = $drug->measury_unit;
			}
		}
		
		return $this->renderAjax('_drug', [
			'receiptDrug' => $receiptDrug,
			'idx'         => $idx,
		]);
	}
	
	/**
	 * Delete drug from ReceiptDrugs of the Receipt model by index
	 * @return string|\yii\webResponse
	 */
	public function actionDeleteDrug()
	{
		$id    = $this->request->get('id');
		$model = isset($id)?$this->findModel((int) $id):new Receipt();
		$idx   = $this->request->get('idx');
		
		if ($this->request->isPost && $model->load($this->request->post())) {
			$receiptDrugs = ReceiptDrugs::loadReceiptDrugs('ReceiptDrugs');
			$searchDrug   = $this->request->post('drug_search');
			$searchPerson = $this->request->post('person_search');
			
			if (isset($idx)) 
				array_splice($receiptDrugs, $idx, 1);

			return $this->render(isset($model->id)?'update':'create', [
				'model'        => $model,
				'receiptDrugs' => $receiptDrugs,
				'searchDrug'   => $searchDrug,
				'searchPerson' => $searchPerson,
			]);
		}
		
		return $this->redirect([isset($id)?'update':'create']);

	}

    /**
     * Search drug for the form
     * @return string|\yii\web\Response
     */
    public function actionSearchDrug()
    {
		$id           = $this->request->get('id');
		$model        = isset($id)?$this->findModel((int) $id):new Receipt();
		$searchDrug   = $this->request->post('drug_search');
		$searchPerson = $this->request->post('person_search');
		
		if ($this->request->isPost && $model->load($this->request->post())) {
			return $this->render(isset($model->id)?'update':'create', [
				'model'        => $model,
				'receiptDrugs' => ReceiptDrugs::loadReceiptDrugs('ReceiptDrugs'),
				'searchDrug'   => $searchDrug,
				'searchPerson' => $searchPerson,
			]);
		}
		
		return $this->redirect([isset($id)?'update':'create']);
	}
	
	/**
	 * Get Drugs list for AJAX
	 * @return string|\yii\webResponse
	 */
	public function actionGetSearchDrugs() 
	{
		$id         = $this->request->get('id');
		$model      = isset($id)?$this->findModel((int) $id):new Receipt();
		$searchDrug = $this->request->get('drug_search');
		
		return $this->renderAjax('_search_drugs', [
			'model'      => $model,
			'searchDrug' => $searchDrug,
		]);
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
