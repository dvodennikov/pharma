<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Document $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="document-form">

    <?php $form = ActiveForm::begin(['action' => $model->id?('/document/update?id=' . $model->id):'/document/create']); ?>

    <?php //echo $form->field($model, 'document_type')->textInput() ?>
    <div class="form-group field-document-document_type">
	<?php if (isset($model->id)) : ?>
		<label class="control-label" for="document_type"><?= \Yii::t('app', 'DocumentType') ?></label><br>
		<input type="text" id="document_type" class="form-control" name="document_type" disabled value="<?= $model->documentType->title ?>">
		<input type="hidden" name="Document[document_type]" value="<?= $model->document_type ?>">
	<?php else : ?>
		<label for="Document[document_type]"><?= \Yii::t('app', 'Document type')?></label><br>
		<select id="Document[document_type]" class="form-control" name="Document[document_type]">
    <?php foreach(common\models\DocumentType::find()->all() as $docType) : ?>
		<option value="<?= $docType->id ?>" <?= ($model->document_type == $docType->id)?'selected':'' ?>>
			<?= htmlspecialchars($docType->title) ?>
		</option>
    <?php endforeach; ?>
		</select>
    <?php endif; ?>
		<div class="help-block"></div>
    </div>

    <?= $form->field($model, 'serial')->textInput(['maxlength' => true, 'pattern' => isset($model->documentType->serial_mask)?$model->documentType->serial_mask:'.{255}']) ?>

    <?= $form->field($model, 'number')->textInput(isset($model->documentType->number_mask)?['pattern' => htmlspecialchars($model->documentType->number_mask)]:[]) ?>
    
    <?= $form->field($model, 'surname')->textInput() ?>
    
    <?= $form->field($model, 'name')->textInput() ?>
    
    <?= $form->field($model, 'second_name')->textInput() ?>

    <?= $form->field($model, 'issue_date')->textInput(['type' => 'date', 'min' => '1900-01-01']) ?>

    <?= $form->field($model, 'issuer')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'expire_date')->textInput(['type' => 'date', 'min' => '1900-01-01']) ?>

    <?php //echo $form->field($model, 'custom_fields')->textInput() ?>
    
    <div id="custom-fields" class="form-group my-3">
		<h3><?= Yii::t('app', 'Custom fields') ?>:</h3>
		
	<?php if ($model->hasErrors('customFields')) : ?>
		<div class="alert alert-danger"><?= $model->getFirstError('customFields') ?></div>
	<?php endif; ?>
    <?php
		//if (!is_null($customFields) && is_array($customFields)) :
		if (!is_null($model->documentType) && !is_null($model->documentType->custom_fields) && is_array($model->documentType->custom_fields)) {
			$idx = -1;
			foreach ($model->documentType->custom_fields as $fieldParams) {
				if (!is_array($fieldParams))
					continue;
				$idx++;
				
				$value = null;
				if (isset($model->custom_fields) && is_array($model->custom_fields))
					foreach ($model->custom_fields as $customField) {
						//var_dump($customField['title']);
						if (is_array($customField) && isset($customField['title']) && ($customField['title'] == $fieldParams['title']))
							$value = isset($customField['value'])?$customField['value']:null;
					}
				
				print $this->render('_custom_fields', [
					'fieldParams' => $fieldParams,
					'value'       => $value,
					'id'          => $model->id,
					'idx'         => $idx
				]);
			}
		}
    ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
        <?php 
			if (is_null($model->id))
				echo Html::submitButton(Yii::t('app', 'Refresh'), ['id' => 'refresh-document', 'class' => 'btn btn-primary', 'formaction' => '/document/refresh?id=' . $model->id]) 
        ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
