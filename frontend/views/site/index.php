<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var common\models\DrugSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$initials = \common\models\Drug::getDistinctTitleInitialLetters();
?>
<div class="site-index">
	<form method="get">
		<input type="hidden" name="<?= Yii::$app->request->csrfParam ?>" value="<?= Yii::$app->request->csrfToken ?>">
		<div id="search-container">
			<input type="text" 
				   id="search-field"
			       name="DrugSearch[title]" 
			       placeholder="<?= Yii::t('app', 'Search drug') ?>" 
			       value="<?= isset(Yii::$app->request->queryParams['DrugSearch']['title'])?Yii::$app->request->queryParams['DrugSearch']['title']:'' ?>">
			<button type="reset" id="reset-button">x</button>
			<button type="submit" id="search-button">
				<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
					<path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
				</svg>
			</button>
		</div>
	</form>
	<nav>
		<ul class="initial-list">
		<?php foreach ($initials as $initial) : ?>
			<li class="initial-item">
			<?= Html::a(mb_substr($initial['initial'], 0, 1), Url::to(['/', 
					'initial' => mb_substr($initial['initial'], 0, 1)
				])) ?>
			</li>
		<?php endforeach; ?>
		</ul>
	</nav>

    <div class="body-content">
	
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [

            [
				'attribute' => 'title',
				'format'    => 'html',
				'content'   => function($model) {
					return Html::a($model->title, Url::to(['/site/view', 'id' => $model->id]));
				}
            ],
            'description',
        ],
    ]); ?>

    </div>
</div>
