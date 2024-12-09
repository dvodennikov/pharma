<?php
/** @var yii\web\View $this */
/** @var common\models\Document $model */
/** @var string $searchDrug */

use yii\helpers\Html;

	$drugs = \common\models\Drug::find()->andWhere(['ilike', 'title', isset($searchDrug)?$searchDrug:''])->all();
	
	$drugsList = ['values' => []];
		
	foreach ($drugs as $drug) {
		$drugsList['values'][] = [
			'value' => $drug->id,
			'title' => $drug->title
		];
	}
	
	echo json_encode($drugsList);
?>
