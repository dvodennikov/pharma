<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Document $model */
/** @var yii\widgets\ActiveForm $form */

$documentTypes = [];
?>

<div class="document-form">

    <?php $form = ActiveForm::begin(['action' => $model->id?('/document/update?id=' . $model->id):'/document/create']); ?>

    <?php //echo $form->field($model, 'document_type')->textInput() ?>
    
    <?= $this->render('_document_type', ['model' => $model]) ?>

	<?= $this->render('_person', ['model' => $model]) ?>
    
    <div>
		<?= \yii\helpers\Html::a(\Yii::t('app', 'Create person'), \yii\helpers\Url::to(['person/create']), ['class' => 'btn btn-primary', 'target' => '_blank']) ?>
	</div>

    <?= $form->field($model, 'serial')->textInput(isset($model->documentType->serial_mask)?['pattern' => htmlspecialchars($model->documentType->serial_mask)]:[]) ?>

    <?= $form->field($model, 'number')->textInput(isset($model->documentType->number_mask)?['pattern' => htmlspecialchars($model->documentType->number_mask)]:[]) ?>
    
    <?= $form->field($model, 'surname')->textInput() ?>
    <?= \common\widgets\SearchSelect::widget(['url' => 'url', 'fieldName' => 'person']); ?>
    
    <?= $form->field($model, 'name')->textInput() ?>
    
    <?= $form->field($model, 'secondname')->textInput() ?>
    
    <?= $form->field($model, 'birthdate')->textInput(['type' => 'date', 'min' => '1900-01-01', 'max' => date('Y-m-d')]) ?>

    <?= $form->field($model, 'issue_date')->textInput(['type' => 'date', 'min' => '1900-01-01', 'max' => date('Y-m-d')]) ?>

    <?= $form->field($model, 'issuer')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'expire_date')->textInput(['type' => 'date', 'min' => '1900-01-01']) ?>

    <?php //echo $form->field($model, 'custom_fields')->textInput() ?>
    
    <?= $this->render('_custom_fields', ['model' => $model]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
        <?php 
			//if (is_null($model->id))
				echo Html::submitButton(Yii::t('app', 'Refresh'), ['id' => 'refresh-document', 'class' => 'btn btn-primary', 'formaction' => '/document/refresh?id=' . $model->id]) 
        ?>
        <?= Html::a(Yii::t('app', 'Cancel'), '/document', ['class' => 'btn btn-danger']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
