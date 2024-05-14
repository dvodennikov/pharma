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
