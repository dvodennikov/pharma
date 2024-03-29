<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Document $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="document-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php //echo $form->field($model, 'document_type')->textInput() ?>
    <div class="form-group field-document-document_type">
    <?php foreach(common\models\DocumentType::find()->all() as $docType) : ?>
		<input type="radio" 
		       id="<?= htmlspecialchars($docType->title) ?>" 
		       name="Document[document_type]" 
		       value="<?= $docType->id ?>" >
		<label for="<?= htmlspecialchars($docType->title) ?>"><?= htmlspecialchars($docType->title) ?></label><br>
    <?php endforeach; ?>
		<div class="help-block"></div>
    </div>

    <?= $form->field($model, 'serial')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'number')->textInput() ?>

    <?= $form->field($model, 'issue_date')->textInput(['type' => 'date', 'min' => '1900-01-01']) ?>

    <?= $form->field($model, 'issuer')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'expire_date')->textInput(['type' => 'date', 'min' => '1900-01-01']) ?>

    <?= $form->field($model, 'custom_fields')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
