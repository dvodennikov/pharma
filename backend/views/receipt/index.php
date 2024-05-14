<?php

use common\models\Receipt;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var common\models\ReceiptSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Receipts');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="receipt-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Receipt'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'number',
            //'person_id',
            [
				'attribute' => 'person_id',
				'format'    => 'html',
				'content'   => function($model) {
					return Html::a(\common\helpers\Pharma::getTextRepresentationForPerson($model->person),
						           Url::to(['receipt/update', 'id' => $model->id]));
				}
            ],
            [
				'attribute' => 'snils',
				'format'    => 'text',
				'content'   => function($model) {
					return $model->person->snils;
				}
            ],
            [
				'attribute' => 'drugs',
				'format'    => 'html',
				'content'   => function($model) {
					if (!is_array($model->drugs) || (count($model->drugs) == 0))
						return '';
						
					$html = '<ul>';
					foreach ($model->drugs as $drug) 
						$html .= '<li>' . $drug . '</li>';
						
					return $html . '</ul>';
				}
            ],
            [
                'class'      => ActionColumn::className(),
                'urlCreator' => function ($action, Receipt $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>
<?php var_dump($dataProvider->getModels()) ?>

    <?php Pjax::end(); ?>

</div>
