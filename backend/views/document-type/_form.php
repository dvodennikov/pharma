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
    
    <?= $this->render('_custom_fields', ['model' => $model]) ?>

    <div class="form-group">
		<?= Html::submitButton(Yii::t('app', 'Add field'), ['id' => 'add-custom-fields', 'class' => 'btn btn-primary', 'formaction' => '/document-type/add-custom-fields?id=' . $model->id]) ?><br>
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success', 'formaction' => '/document-type/' . (isset($model->id)?('update?id=' . $model->id):'create')]) ?>
        <?= Html::a(Yii::t('app', 'Cancel'), '/document-type', ['class' => 'btn btn-danger']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
