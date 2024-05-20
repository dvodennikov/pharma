<?php

namespace backend\controllers;

use common\models\User;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use Yii;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
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
                'access' => [
					'class' => AccessControl::className(),
					'rules' => [
						//~ [
							//~ 'allow' => true,
							//~ 'roles' => ['@'],
						//~ ],
						[
							'allow' => true,
							'actions' => ['index', 'view'],
							'roles' => ['createUser', 'updateUser'],
						],
						[
							'allow' => true,
							'actions' => ['create'],
							'roles' => ['createUser'],
						],
						[
							'allow' => true,
							'actions' => ['update', 'delete'],
							'roles' => ['updateUser'],
						],
					],
                ],
            ]
        );
    }

    /**
     * Lists all User models.
     *
     * @return string
     */
    public function actionIndex()
    {
		//~ if (!Yii::$app->user->can('createUser') ||
			//~ !Yii::$app->user->can('updateUser'))
			//~ return $this->redirect(['site/index']);
		
        $dataProvider = new ActiveDataProvider([
            'query' => User::find(),
            'pagination' => [
                'pageSize' => \Yii::$app->params['pageSize']
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param int $id
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
		//~ if (!Yii::$app->user->can('createUser') ||
			//~ !Yii::$app->user->can('updateUser'))
			//~ return $this->redirect(['site/index']);
		
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
		//~ if (!Yii::$app->user->can('createUser'))
			//~ return $this->redirect(['site/index']);
		
        $model = new User();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
				$model->setRole(true);
				
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
		//~ if (!Yii::$app->user->can('updateUser'))
			//~ return $this->redirect(['site/index']);
		
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
			$model->setRole(true);
			
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
		//~ if (!Yii::$app->user->can('updateUser'))
			//~ return $this->redirect(['site/index']);
		
        $this->findModel($id)->delete();
        Yii::$app->authManager->revokeAll($id);

        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
