<?php
/** @var yii\web\View $this */
/** @var common\models\ReceiptDrugs $receiptDrug */
/** @var int $idx */

use yii\helpers\Html;

if (!isset($idx))
	$idx = 0;
?>

	<div class="control-group col-sm-6">
		<input type="hidden" 
			   name="ReceiptDrugs[<?= $idx ?>][id]" 
			   value="<?= isset($receiptDrug->id)?$receiptDrug->id:'' ?>">
		<input type="hidden" 
			   name="ReceiptDrugs[<?= $idx ?>][drug_id]" 
			   value="<?= $receiptDrug->drug_id ?>">
		<label for="receipt-drug-title-<?= $idx ?>"
			   class="control-label">
		<?= \Yii::t('app', 'Drug') ?>
		</label>
		<input type="text" id="receipt-drug-title-<?= $idx ?>" 
			   name="ReceiptDrugs[<?= $idx ?>][title]" 
			   class="form-control" 
			   readonly 
			   value="<?= (isset($receiptDrug->drug->title)?$receiptDrug->drug->title:'none') . ((isset($receiptDrug->drug->description) && ($receiptDrug->drug->description != ''))?(' | ' . $receiptDrug->drug->description):'') ?>">
		<label for="receipt-drug-quantity"
			   class="control-label">
		<?= \Yii::t('app', 'Quantity') ?>
		</label>
		<input type="text" id="receipt-drug-quantity-<?= $idx ?>" 
			   name="ReceiptDrugs[<?= $idx ?>][quantity]" 
			   class="form-control" 
			   pattern="\d+" 
			   value="<?= $receiptDrug->quantity ?>">
		<div class="help-block">
		<?php
		if (isset($receiptDrug) && $receiptDrug->hasErrors('quantity')) {
			echo $receiptDrug->getFirstError('quantity');
		}
		?>
		</div>
		<?= Html::SubmitButton(\Yii::t('app', 'Delete drug'), [
			'class'      => 'delete-drug btn btn-danger',
			'formaction' => '/receipt/delete-drug?' . (isset($model->id)?('id=' . $model->id . '&'):'') . 'idx=' . $idx,
		]) ?>
	</div>
