<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Drug $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="drug-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'measury')->textInput() ?>

    <?php //echo $form->field($model, 'measury_unit')->textInput() ?>
    
    <div class="form-group">
		<label for="drug-measury_unit" class="control-label"><?= \Yii::t('app', 'Drug') ?></label>
		<select id="drug-measury_unit" name="Drug[measury_unit]" class="form-control">
	<?php 
		$units = \common\models\Unit::find()->all();
		foreach ($units as $unit) :
	?>
		<option value="<?= $unit->id ?>" <?= ($model->measury_unit == $unit->id)?'selected':'' ?>>
	<?= $unit->title ?>
		</option>
	<?php endforeach; ?>
		</select>
    </div>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
        <?= Html::a(Yii::t('app', 'Cancel'), Url::to(['/drug/index']), ['class' => 'btn btn-danger']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
