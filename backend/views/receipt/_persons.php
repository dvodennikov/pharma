<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Receipt $model */
/** @var string $searchPerson */

?>

	<noscript>
    <div class="form-group row">
		<div class="col-sm-6">
			<label for="receipt-person_id" 
				   class="control-label">
			<?= \Yii::t('app', 'Person') ?>
			</label>
			<select id="receipt-person_id" 
					name="Receipt[person_id]" 
					class="form-control">
			<?= $this->render('_person', [
				'model' => $model,
				'searchPerson' => isset($searchPerson)?$searchPerson:null
			]) ?>
			</select>
		</div>
		<div class="col-sm-6">
			<label for="person_search" 
				   class="control-label">
			<?= \Yii::t('app', 'Person search') ?>
			</label>
			<input type="text" 
				   id="person_search" 
				   name="person_search" 
				   class="form-control" 
				   value="<?= isset($searchPerson)?$searchPerson:'' ?>">
			<?= Html::submitButton(\Yii::t('app', 'Search'), [
				'class'      => 'btn btn-primary', 
				'formaction' => '/receipt/search-person' . (isset($model->id)?('?id=' . $model->id):'')
			]) ?>
		</div>
    </div>
    </noscript>
