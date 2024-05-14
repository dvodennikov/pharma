<?php
/** @var yii\web\View $this */
/** @var common\models\Document $model */

use yii\helpers\Html;

?>

	<div id="custom-fields" class="form-group my-3">
		<h4><?= Yii::t('app', 'Custom fields') ?>:</h4>
		
	<?php if ($model->hasErrors('custom_fields')) : ?>
		<div class="alert alert-danger"><?= $model->getFirstError('custom_fields') ?></div>
	<?php endif; ?>
    <?php
		if (!is_null($model->documentType) && !is_null($model->documentType->custom_fields) && is_array($model->documentType->custom_fields)) :
			$idx = -1;
			foreach ($model->documentType->custom_fields as $fieldParams) :
				if (!is_array($fieldParams))
					continue;
				$idx++;
				
				$value = null;
				if (isset($model->custom_fields) && is_array($model->custom_fields))
					foreach ($model->custom_fields as $customField) {
						if (is_array($customField) && isset($customField['title']) && ($customField['title'] == $fieldParams['title']))
							$value = isset($customField['value'])?$customField['value']:null;
					}
			
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
						   <?= (isset($fieldParams['mask']) && ($fieldParams['mask'] != ''))?('pattern="' . $fieldParams['mask'] . '"'):'' ?>
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
	<?php
			endforeach;
		endif;
    ?>
    </div>
