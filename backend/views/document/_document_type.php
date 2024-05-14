<?php
/** @var yii\web\View $this */
/** @var common\models\Document $model */

use yii\helpers\Html;
?>

	<div class="form-group field-document-document_type">
	<?php if (isset($model->id)) : ?>
		<label class="control-label" for="document_type"><?= \Yii::t('app', 'Document type') ?></label><br>
		<input type="text" id="document_type" class="form-control" name="document_type" disabled value="<?= $model->documentType->title ?>">
		<input type="hidden" name="Document[document_type]" value="<?= $model->document_type ?>">
	<?php else : ?>
		<label for="Document[document_type]"><?= \Yii::t('app', 'Document type')?></label><br>
		<select id="Document[document_type]" class="form-control document-type" name="Document[document_type]">
    <?php $documentTypes = common\models\DocumentType::find()->all();
		//init model by first documentType selected
		if (!isset($model->document_type) && isset($documentTypes[0])) {
			$model->document_type = $documentTypes[0]->id;
			$model->custom_fields = $documentTypes[0]->custom_fields;
		}
		foreach($documentTypes as $docType) : ?>
		<option value="<?= $docType->id ?>" <?= ($model->document_type == $docType->id)?'selected':'' ?>>
			<?= htmlspecialchars($docType->title) ?>
		</option>
    <?php endforeach; ?>
		</select>
    <?php endif; ?>
		<div class="help-block"></div>
    </div>
