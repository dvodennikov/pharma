<?php 
/** @var yii\web\View $this */

use common\models\Drug;
use yii\helpers\Html;
use yii\helpers\Url;
use common\helpers\Pharma;

$drugs = Drug::getLastDrugs();
?>
<h2><?= \Yii::t('app', 'Drugs') ?></h2>
<ul>
<?php foreach ($drugs as $drug) : ?>
	<li><?= Html::a($drug->title, Url::to(['/drug/update', 'id' => $drug->id])) ?></li>
<?php endforeach; ?>
</ul>
<p><?= Html::a(\Yii::t('app', 'Add drug'), Url::to(['/drug/create']), ['class' => 'btn btn-outline-success']) ?></p>
