<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Receipt $model */
/** @var common\models\ReceiptDrugs[] $receiptDrugs */
/** @var string $searchDrug */

$idx = 0;
//throw new \yii\base\NotSupportedException(print_r($receiptDrugs, true));
?>

	<div id="drugs" class="form-group row">
    <h4><?= \Yii::t('app', 'Drugs') ?></h4>
    <?php if ($model->hasErrors('drugs')) : ?>
		<div class="alert alert-danger"><?= $model->getFirstError('drugs') ?></div>
	<?php endif; ?>
    <?php foreach ($receiptDrugs as $receiptDrug) : ?>
    <?= $this->render('_drug', [
		'receiptDrug' => $receiptDrug,
		'idx'         => $idx,
    ]) ?>
	<?php $idx++ ?>
	<?php endforeach; ?>
	</div>
	
	<h4><?= \Yii::t('app', 'Add drug') ?></h4>
	<div class="form-group row">
		<div class="col-sm-6">
			<label for="receipt-drug-drug_id" 
				   class="control-label">
			<?= \Yii::t('app', 'Drug') ?>
			</label>
			<select id="receipt-drug-drug_id" 
					name="AddDrug[drug_id]" 
					class="form-control">
			<?= $this->render('_search_drugs', [
				'model'      => $model,
				'searchDrug' => isset($searchDrug)?$searchDrug:null
			]) ?>
			</select>
			<label for="receipt-drug-quantity"
				   class="control-label">
			<?= \Yii::t('app', 'Quantity') ?>
			</label>
			<input type="text" 
				   id="receipt-drug-quantity" 
				   name="AddDrug[quantity]" 
				   class="form-control">
			<?= Html::SubmitButton(\Yii::t('app', 'Add drug'), [
				'id'         => 'add-drug',
				'class'      => 'btn btn-primary',
				'formaction' => '/receipt/add-drug' . (isset($model->id)?('?id=' . $model->id):'')
			]) ?>
		</div>
		<div class="col-sm-6">
			<label for="drug_search"
				   class="control-label">
			<?= \Yii::t('app', 'Search') ?>
			</label>
			<input id="drug_search" 
				   name="drug_search" 
				   class="form-control" 
				   value="<?= isset($searchDrug)?$searchDrug:'' ?>">
			<?= Html::submitButton(\Yii::t('app', 'Search'), [
				'class'      => 'btn btn-primary',
				'formaction' => '/receipt/search-drug' . (isset($model->id)?('?id=' . $model->id):'')
			]) ?>
		</div>
	</div>
