<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Receipt $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="receipt-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'person_id')->textInput() ?>

    <?= $form->field($model, 'drug_id')->textInput() ?>

    <?= $form->field($model, 'quantity')->textInput() ?>

    <?php //echo $form->field($model, 'unit_id')->textInput() ?>
    
    <div class="form-group">
		<label for="receipt-unit_id" class="control-label"><?= \Yii::t('app', 'Unit') ?></label>
		<select id="receipt-unit_id" name="Receipt[unit_id]" class="form-control">
	<?php foreach (\common\models\Unit::find()->all() as $unit) : ?>
		<option value="<?= $unit->id ?>" <?= ($model->unit_id == $unit->id)?'selected':'' ?>>
	<?= $unit->title ?>
		</option>
	<?php endforeach; ?>
		</select>
    </div>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
