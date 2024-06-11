<?php
/** @var yii\web\View $this */
/** @var \common\models\DocumentType $model */

use yii\helpers\Html;

?>

<input type="hidden" value="<?= json_encode($model->custom_fields) ?>">
    
    <div id="custom-fields" class="form-group">
		<h3><?= Yii::t('app', 'Custom fields') ?>:</h3>
		
	<?php if ($model->hasErrors('custom_fields')) : ?>
		<div class="alert alert-danger"><?= $model->getFirstError('custom_fields') ?></div>
	<?php endif; ?>
    <?php
		//if (!is_null($customFields) && is_array($customFields)) :
		if (!is_null($model->custom_fields) && is_array($model->custom_fields)) :
			$idx = -1;
			foreach ($model->custom_fields as $customField) :
				if (!is_array($customField))// || is_null($customField->title))
					continue;
				$idx++;
				
				echo $this->render('_custom_field', [
					'model'       => $model,
					'customField' => $customField,
					'idx'         => $idx,
					'id'          => $model->id
				]);
			
			endforeach;
		endif;
    ?>
    </div>

