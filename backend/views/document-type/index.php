<?php

use common\models\DocumentType;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var common\models\DocumentTypeSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Document Types');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="document-type-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Document Type'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            //'title',
            [
				'attribute' => 'title',
				'format' => 'html',
				'content' => function($model) {
					return \yii\helpers\Html::a($model->title, \yii\helpers\Url::to(['/document-type/update', 'id' => $model->id]));
				},
            ],
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
                'urlCreator' => function ($action, DocumentType $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
