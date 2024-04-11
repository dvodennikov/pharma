<?php
/** @var yii\web\View $this */
/** @var array $fieldParams */
/** @var string $value */
/** @var common\models\Document $model */
/** @var int $idx */

use yii\helpers\Html;

if (!isset($customField))
	$customField = [];
if (!isset($idx))
	$idx = 0;
/*if (!isset($id))
	$id = 0;*/
?>

<div class="control-group py-2">
	<input type="hidden" 
	       id="Document[custom_fields][<?= $idx ?>][title]" 
	       name="Document[custom_fields][<?= $idx ?>][title]" 
	       value="<?= $fieldParams['title'] ?>">
	<label for="Document[custom_fields][<?= $idx ?>][value]">
		<?= Yii::t('app', $fieldParams['title']) ?>
	</label>
	<input type="text" 
	       id="Document[custom_fields][<?= $idx ?>][value]" 
	       name="Document[custom_fields][<?= $idx ?>][value]" 
	       class="form-control" 
	       size="15"
	       <?= isset($fieldParams['mask'])?('pattern="' . $fieldParams['mask'] . '"'):'' ?>
	       value="<?= isset($value)?$value:'' ?>"
	       placeholder="<?= Yii::t('app', $fieldParams['title']) ?>">
	<div class="help-block">
		<?php
		if (isset($model) && $model->hasErrors('custom_fields')) {
			foreach ($model->getErrors('custom_fields') as $error) {
				if (str_contains($error, ' ' . $fieldParams['title'] . ' '))
					echo $error;
			}
		}
		?>
	</div>
</div>
