<?php 
/** @var yii\web\View $this */

use common\models\Receipt;

$receipts = Receipt::find()->limit(5)->all();
?>
<h2><?= \Yii::t('app', 'Receipts') ?></h2>
<ul>
<?php foreach ($receipts as $receipt) : ?>
	<li><?= $receipt->number ?></li>
<?php endforeach; ?>
</ul>
