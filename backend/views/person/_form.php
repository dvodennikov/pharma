<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Person $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="person-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'surname')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'secondname')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'birthdate')->textInput(['type' => 'date', 'min' => '1900-01-01']) ?>

    <?= $form->field($model, 'snils')->textInput() ?>

    <?= $form->field($model, 'polis')->textInput() ?>

    <?= $form->field($model, 'address')->textArea(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
