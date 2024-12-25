<?php

use common\models\User;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Users');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create User'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
				'attribute' => 'username',
				'value' => function($model) {
					return yii\helpers\Html::a($model->username, ['/user/update', 'id' => $model->id]);
				},
				'format' => 'html',
			],
            'email:email',
            [
				'attribute' => 'status',
				'value' => function($model) {
					return Yii::t('app', ($model->status == User::STATUS_ACTIVE)?'Active':'Disabled');
				},
			],
            [
				'attribute' => 'created_at',
				//~ 'value' => function($model) {
					//~ return date('d/m/Y H:i:s', $model->created_at);
				//~ },
				'format' => ['date', 'php:d/m/Y H:i:s'],
			],
            [
				'attribute' => 'updated_at',
				/*'value' => function($model) {
					//return date('d/m/Y H:i:s', $model->updated_at);
					return Yii::$app->formatter->asDateTime($model->updated_at);
				},*/
				'format' => ['date', 'php:d/m/Y H:i:s'],
			],
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, User $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
        'pager' => [
			'options' => [
				'class' => 'pagination',
			],
			'pageCssClass' => 'page-item',
			'linkOptions' => [
				'class' => 'page-link',
			],
        ],
    ]); ?>


</div>
