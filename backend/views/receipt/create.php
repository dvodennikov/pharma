<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Receipt $model */
/** @var common\models\ReceiptDrugs[] $receiptDrugs */
/** @var string $searchPerson */
/** @var string $searchDrug */

$this->title = Yii::t('app', 'Create Receipt');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Receipts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="receipt-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model'        => $model,
        'receiptDrugs' => isset($receiptDrugs)?$receiptDrugs:[],
        'searchPerson' => isset($searchPerson)?$searchPerson:null,
        'searchDrug'   => isset($searchDrug)?$searchDrug:null,
    ]) ?>

</div>
