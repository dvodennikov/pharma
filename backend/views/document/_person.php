<?php
/** @var yii\web\View $this */
/** @var common\models\Document $model */

use yii\helpers\Html;
?>

<div class="form-group field-document-person_id">
	<label for="Document[person_id]"><?= \Yii::t('app', 'Person') ?></label>
	<select id="Document[person_id]" class="form-control person-id" name="Document[person_id]">
		
    <?php 
		$persons = [];
		if (isset($model->surname) || isset($model->name) || isset($model->secondname) || isset($model->birthdate))
			$persons = common\models\Person::getPersonsBySurnameNameSecondnameBirthdate([ 
				'surname'    => $model->surname, 
				'name'       => $model->name,
				'secondname' => $model->secondname,
				'birthdate'  => $model->birthdate
			]);
		//init model by first person selected
		if (!isset($model->person_id) && isset($persons[0])) {
			$model->person_id = $persons[0]->id;
		}
		foreach($persons as $person) : ?>
		<option value="<?= $person->id ?>" <?= ($model->person_id == $person->id)?'selected':'' ?>>
			<?= htmlspecialchars($person->surname . ' ' . $person->name . ' ' . (is_null($person->secondname)?'-':$person->secondname) . ' ' . $person->birthdate) ?>
		</option>
    <?php endforeach; ?>
    <?php if (count($persons) == 0) : ?>
    <?php if (isset($model->person_id)) : ?>
		<option value="<?= $model->person_id ?>" selected>
			<?= htmlspecialchars(common\models\Person::getPersonTextRepresentationById($model->person_id)) ?>
		</option>
	<?php else : ?>
		<option>
			<?= \Yii::t('app', 'Fill surname, name, secondname and birthdate fields and press refresh button') ?>
		</option>
	<?php endif; ?>
		<option value=""><?= Yii::t('app', 'Not found') ?></option>
    <?php endif; ?>
	</select>
		<div class="help-block"></div>
</div>
