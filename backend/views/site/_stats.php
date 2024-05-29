<?php 
/** @var yii\web\View $this */

use \Yii;

?>
<h2><?= Yii::t('app', 'Stats') ?></h2>
<ul>
	<li>
	<?= Yii::t('app', 'Receipts') ?>:
	<?= \common\models\Receipt::find()->count() ?>
	</li>
	<li>
	<?= Yii::t('app', 'Drugs') ?>:
	<?= \common\models\Drug::find()->count() ?>
	</li>
	<li>
	<?= Yii::t('app', 'Persons') ?>:
	<?= \common\models\Person::find()->count() ?>
	</li>
	<li>
	<?= Yii::t('app', 'Documents') ?>:
	<?= \common\models\Document::find()->count() ?>
	</li>
	<li>
	<?= Yii::t('app', 'Document types') ?>:
	<?= \common\models\DocumentType::find()->count() ?>
	</li>
	<li>
	<?= Yii::t('app', 'Units') ?>:
	<?= \common\models\Unit::find()->count() ?>
	</li>
</ul>
