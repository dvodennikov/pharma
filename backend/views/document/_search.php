<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\DocumentSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="document-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'document_type') ?>

    <?= $form->field($model, 'serial') ?>

    <?= $form->field($model, 'number') ?>

    <?= $form->field($model, 'issue_date') ?>

    <?php // echo $form->field($model, 'issuer') ?>

    <?php // echo $form->field($model, 'expire_date') ?>

    <?php // echo $form->field($model, 'custom_fields') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
