<?php
/** @var yii\web\View $this */
/** @var common\models\Receipt $model */
/** @var yii\widgets\ActiveForm $form */
/** @var common\models\ReceiptDrugs[] $receiptDrugs */
/** @var string $searchPerson */
/** @var string $searchDrug */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="receipt-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'number')->textInput(['maxlength' => true]) ?>

    <?php //echo $form->field($model, 'person_id')->textInput() ?>
 
    <?= $this->render('_persons', [
		'model'        => $model,
		'searchPerson' => $searchPerson
	]) ?>
	<?php 
		$searchSelectOptions = [
			'class'          => 'form-control',
			'url'            => 'http://admin.pharma.localhost/receipt/get-persons', 
			'searchParam'    => 'person_search',
			'fieldName'      => 'Receipt[person_id]',
			'label'          => Yii::t('app', 'Person'),
			'searchHint'     => Yii::t('app', 'Search person'),
			'minCharsSearch' => 5,
			'searchDelay'    => 5000,
			'mimic'          => false//'select#receipt-drug-drug_id'
		];
		
		if (isset($model->person_id)) {
			$searchSelectOptions['value']   = $model->person_id;
			$searchSelectOptions['caption'] = htmlspecialchars(common\models\Person::getPersonTextRepresentationById($model->person_id));
		}
	?>
	<?= \common\widgets\SearchSelect::widget($searchSelectOptions); ?>
	
	<?= $form->field($model, 'issue_date')->textInput(['type' => 'date', 'max' => date('Y-m-d')]) ?>
	
	<?= $form->field($model, 'sell_date')->textInput(['type' => 'date', 'max' => date('Y-m-d')]) ?>
    
	<?= $this->render('_drugs', [
		'model'        => $model,
		'receiptDrugs' => $receiptDrugs,
		'searchDrug'   => $searchDrug
	]) ?>


    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), [
			'class' => 'btn btn-success', 
			'formaction' => '/receipt/' . (isset($model->id)?('update?id=' . $model->id):'create')
		]) ?>
		<?= Html::a(Yii::t('app', 'Cancel'), \yii\helpers\Url::to(['/receipt']), ['class' => 'btn btn-danger']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
