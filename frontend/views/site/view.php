<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Drug $model */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Drugs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="drug-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id',
            'title',
            'description',
            //'measury',
            //'measury_unit',
            /*[
				'attribute' => 'updated_at',
				'format'    => 'text',
				'value'     => function($model) {
					return isset($model->updated_at)?\Yii::$app->getFormatter()->asDatetime($model->updated_at):\Yii::t('app' ,'none');
				}
            ],
            [
				'attribute' => 'updated_by',
				'format'    => 'text',
				'value'     => function($model) {
					return isset($model->updated_by)?\common\models\User::getUsernameById($model->updated_by):\Yii::t('app' ,'none');
				}
            ],*/
        ],
    ]) ?>

</div>
