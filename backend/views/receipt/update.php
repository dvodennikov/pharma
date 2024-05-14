<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Receipt $model */
/** @var common\models\ReceiptDrugs $receiptDrugs */
/** @var string $searchPerson */
/** @var string $searchDrug */

$this->title = Yii::t('app', 'Update Receipt: {name}', [
    'name' => $model->id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Receipts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="receipt-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model'        => $model,
        'receiptDrugs' => $receiptDrugs,
        'searchPerson' => isset($searchPerson)?$searchPerson:null,
        'searchDrug'   => isset($searchDrug)?$searchDrug:null,
    ]) ?>

</div>
