<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\DocumentType $model */
/** @var yii\widgets\ActiveForm $form */
/** @var array $customFields */
?>

<div class="document-type-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'serial_mask')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'number_mask')->textInput(['maxlength' => true]) ?>

    <?php // echo $form->field($model, 'custom_fields', ['inputOptions' => ['type'=> 'hidden', 'value' => json_encode($model->custom_fields)]])->textArea() ?>
    <input type="hidden" value="<?= json_encode($model->custom_fields) ?>">
    
    <div id="custom-fields" class="form-group my-3">
		<h3><?= Yii::t('app', 'Custom fields') ?>:</h3>
		
	<?php if ($model->hasErrors('customFields')) : ?>
		<div class="alert alert-danger"><?= $model->getFirstError('customFields') ?></div>
	<?php endif; ?>
    <?php
		//if (!is_null($customFields) && is_array($customFields)) :
		if (!is_null($model->custom_fields) && is_array($model->custom_fields)) :
			$idx = -1;
			foreach ($model->custom_fields as $customField) :
				if (!is_array($customField))// || is_null($customField->title))
					continue;
				$idx++;
				print $this->render('_custom_fields', [
					'customField' => $customField,
					'id'          => $model->id,
					'idx'         => $idx
				]);
			endforeach;
		endif;
    ?>
    </div>

    <div class="form-group py-5">
		<?= Html::submitButton(Yii::t('app', 'Add field'), ['id' => 'add-custom-fields', 'class' => 'btn btn-primary my-3', 'formaction' => '/document-type/add-custom-fields?id=' . $model->id]) ?><br>
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success', 'formaction' => '/document-type/' . (isset($model->id)?('update?id=' . $model->id):'create')]) ?>
        <?= Html::a(Yii::t('app', 'Cancel'), '/document-type', ['class' => 'btn btn-danger']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
