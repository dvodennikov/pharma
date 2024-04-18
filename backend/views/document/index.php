<?php

use common\models\Document;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var common\models\DocumentSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Documents');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="document-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Document'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            //'document_type',
            [
				'attribute' => 'document_type',
				'format' => 'text',
				'content' => function($model) {
					return \yii\helpers\Html::a($model->documentType->title, \yii\helpers\Url::to(['/document/update', 'id' => $model->id]));
				},
				
            ],
            'serial',
            'number',
            'surname',
            'name',
            'secondname',
            'issue_date',
            //'issuer',
            //'expire_date',
            //'custom_fields',
            [
				'attribute' => 'custom_fields',
				'format' => 'text',
				'content' => function($model) {
					return json_encode($model->custom_fields);
				},
            ],
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Document $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
