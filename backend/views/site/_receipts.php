<?php 
/** @var yii\web\View $this */

use common\models\Receipt;
use yii\helpers\Html;
use yii\helpers\Url;
use common\helpers\Pharma;

$receipts = Receipt::getLastReceipts(isset(\Yii::$app->params['controlPanelLastReceiptsNumber'])?\Yii::$app->params['controlPanelLastReceiptsNumber']:5);
?>
<h2><?= \Yii::t('app', 'Receipts') ?></h2>
<ul>
<?php foreach ($receipts as $receipt) : ?>
	<li><?= Pharma::getTextRepresentationForReceipt($receipt, ['url' => Url::to(['/receipt/update', 'id' => $receipt->id])]) ?></li>
<?php endforeach; ?>
</ul>
<p><?= Html::a(\Yii::t('app', 'Add receipt'), Url::to(['/receipt/create']), ['class' => 'btn btn-outline-success']) ?></p>
