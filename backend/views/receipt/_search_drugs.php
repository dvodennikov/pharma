<?php
/** @var yii\web\View $this */
/** @var common\models\Document $model */
/** @var string $searchDrug */

use yii\helpers\Html;
?>

	<?php $drugs = \common\models\Drug::find()->andWhere(['ilike', 'title', isset($searchDrug)?$searchDrug:''])->all() ?>

	<?php foreach ($drugs as $drug) : ?>
		<option value="<?= $drug->id ?>">
	<?= $drug->title . ((isset($drug->description) && ($drug->description != ''))?(' | ' . $drug->description):'') ?>
		</option>
	<?php endforeach; ?>
    <?php if (count($drugs) == 0) : ?>
    	<option value=""><?= Yii::t('app', 'Not found') ?></option>
    <?php endif; ?>

