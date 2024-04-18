<?php

use yii\helpers\Html;
use yii\helpers\Url;
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
    
    <h4><?= \Yii::t('app', 'Documents') ?></h4>
    <ul>
    <?php foreach ($model->getAllDocuments() as $document) : ?>
		<li>
    <?php echo Html::a($document->document_type . ' ' . (isset($document->serial)?($document->serial . ' '):'') . $document->number, 
					   Url::to(['document/update', 'id' => $document->id]), ['target' => '_blank']) ?>
		</li>
    <?php endforeach; ?>
    </ul>
    <div class="form-group">
        <?php echo Html::a(Yii::t('app', 'Create document'), 
						   Url::to(['document/create', 'person_id' => $model->id]), 
						   ['class' => 'btn btn-primary', 'target' => '_blank']) ?>
    </div>


    <div class="form-group py-3">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
        <?= Html::a(Yii::t('app', 'Cancel'), '/person', ['class' => 'btn btn-danger']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
