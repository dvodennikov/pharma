<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Document $model */
/** @var yii\widgets\ActiveForm $form */

$documentTypes = [];
?>

<div class="document-form">

    <?php $form = ActiveForm::begin(['action' => $model->id?('/document/update?id=' . $model->id):'/document/create']); ?>

    <?php //echo $form->field($model, 'document_type')->textInput() ?>
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

	<div class="form-group field-document-person_id">
		<label for="Document[person_id]"><?= \Yii::t('app', 'Person') ?></label>
		<select id="Document[person_id]" class="form-control person-id" name="Document[person_id]">
	<?= $this->render('_person', ['model' => $model]) ?>
		</select>
		<div class="help-block"></div>
    </div>
    
    <div>
		<?= \yii\helpers\Html::a(\Yii::t('app', 'Create person'), \yii\helpers\Url::to(['person/create']), ['class' => 'btn btn-primary', 'target' => '_blank']) ?>
	</div>

    <?= $form->field($model, 'serial')->textInput(isset($model->documentType->serial_mask)?['pattern' => htmlspecialchars($model->documentType->serial_mask)]:[]) ?>

    <?= $form->field($model, 'number')->textInput(isset($model->documentType->number_mask)?['pattern' => htmlspecialchars($model->documentType->number_mask)]:[]) ?>
    
    <?= $form->field($model, 'surname')->textInput() ?>
    
    <?= $form->field($model, 'name')->textInput() ?>
    
    <?= $form->field($model, 'secondname')->textInput() ?>
    
    <?= $form->field($model, 'birthdate')->textInput(['type' => 'date', 'min' => '1900-01-01']) ?>

    <?= $form->field($model, 'issue_date')->textInput(['type' => 'date', 'min' => '1900-01-01']) ?>

    <?= $form->field($model, 'issuer')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'expire_date')->textInput(['type' => 'date', 'min' => '1900-01-01']) ?>

    <?php //echo $form->field($model, 'custom_fields')->textInput() ?>
    
    <div id="custom-fields" class="form-group my-3">
		<h4><?= Yii::t('app', 'Custom fields') ?>:</h4>
		
	<?php if ($model->hasErrors('custom_fields')) : ?>
		<div class="alert alert-danger"><?= $model->getFirstError('custom_fields') ?></div>
	<?php endif; ?>
    <?php
		if (!is_null($model->documentType) && !is_null($model->documentType->custom_fields) && is_array($model->documentType->custom_fields)) {
			$idx = -1;
			foreach ($model->documentType->custom_fields as $fieldParams) {
				if (!is_array($fieldParams))
					continue;
				$idx++;
				
				$value = null;
				if (isset($model->custom_fields) && is_array($model->custom_fields))
					foreach ($model->custom_fields as $customField) {
						if (is_array($customField) && isset($customField['title']) && ($customField['title'] == $fieldParams['title']))
							$value = isset($customField['value'])?$customField['value']:null;
					}
				
				print $this->render('_custom_fields', [
					'fieldParams' => $fieldParams,
					'value'       => $value,
					'model'       => $model,
					'idx'         => $idx
				]);
			}
		}
    ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
        <?php 
			//if (is_null($model->id))
				echo Html::submitButton(Yii::t('app', 'Refresh'), ['id' => 'refresh-document', 'class' => 'btn btn-primary', 'formaction' => '/document/refresh?id=' . $model->id]) 
        ?>
        <?= Html::a(Yii::t('app', 'Cancel'), '/document', ['class' => 'btn btn-danger']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
