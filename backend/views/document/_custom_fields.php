<?php
/** @var yii\web\View $this */
/** @var array fieldParams */
/** @var string value */
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
	<input type="hidden" 
	       id="customFields[<?= $idx ?>][title]" 
	       name="customFields[<?= $idx ?>][title]" 
	       value="<?= $fieldParams['title'] ?>">
	<label for="customFields[<?= $idx ?>][value]">
		<?= Yii::t('app', $fieldParams['title']) ?>
	</label>
	<input type="text" 
	       id="customFields[<?= $idx ?>][value]" 
	       name="customFields[<?= $idx ?>][value]" 
	       class="form-control" 
	       size="15"
	       value="<?= isset($value)?$value:'' ?>"
	       placeholder="<?= Yii::t('app', $fieldParams['title']) ?>">
	<br>
</div>
