<?php
/** @var yii\web\View $this */
/** @var array customField */
/** @var int $id */
/** @var int $idx */

use yii\helpers\Html;

if (!isset($customField))
	$customField = [];
if (!isset($idx))
	$idx = 0;
if (!isset($id))
	$id = 0;
?>

<div class="control-group py-2">
	<label for="DocumentType[custom_fields][<?= $idx ?>][title]">
		<?= Yii::t('app', 'Title') ?>
	</label>
	<input type="text" 
	       id="DocumentType[custom_fields][<?= $idx ?>][title]" 
	       name="DocumentType[custom_fields][<?= $idx ?>][title]" 
	       class="form-control" 
	       size="15"
	       value="<?= isset($customField['title'])?$customField['title']:'' ?>"
	       placeholder="<?= Yii::t('app', 'Title') ?>">
	<br>
	<label for="DocumentType[custom_fields][<?= $idx ?>][mask]">
		<?= Yii::t('app', 'Mask') ?>
	</label>
	<input type="text" 
	       id="DocumentType[custom_fields][<?= $idx ?>][mask]" 
	       name="DocumentType[custom_fields][<?= $idx ?>][mask]" 
	       class="form-control" 
	       size="5"
	       value="<?= isset($customField['mask'])?$customField['mask']:'' ?>"
	       placeholder="<?= Yii::t('app', 'Mask') ?>">
	<br>
	<?= Html::submitButton(Yii::t('app', 'Delete field'), [
		'class' => 'delete-custom-field btn btn-danger', 
		'formaction' => '/document-type/delete-custom-field?id=' . $id . '&idx=' . $idx
	]) ?>
</div>
