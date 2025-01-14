<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\DocumentTypeSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="document-type-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'title') ?>

    <?= $form->field($model, 'custom_fields') ?>
    <div class="form-group field-documenttypesearch-custom_fields_text">
		<label class="control-label" for="documenttypesearch-custom_fields_text">
		<input type="text" class="form-control" name="documenttypesearch-custom_fields_text" maxlength="255">
	</div>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
