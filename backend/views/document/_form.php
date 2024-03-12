<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Document $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="document-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'document_type')->textInput() ?>

    <?= $form->field($model, 'serial')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'number')->textInput() ?>

    <?= $form->field($model, 'issue_date')->textInput() ?>

    <?= $form->field($model, 'issuer')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'expire_date')->textInput() ?>

    <?= $form->field($model, 'custom_fields')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
