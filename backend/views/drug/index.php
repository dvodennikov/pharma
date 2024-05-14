<?php

use common\models\Drug;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var common\models\DrugSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Drugs');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="drug-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Drug'), ['create'], ['class' => 'btn btn-success']) ?>
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
				'format'    => 'html',
				'content'   => function($model) {
					return Html::a($model->title, Url::to(['/drug/update', 'id' => $model->id]));
				}
            ],
            'description',
            'measury',
            'measury_unit',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Drug $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
