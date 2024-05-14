<?php
/** @var yii\web\View $this */
/** @var common\models\Document $model */
/** @var string $searchPerson */

use yii\helpers\Html;
?>

	<?php $persons = \common\models\Person::getPersonsBySurnameNameSecondnameBirthdate(['surname' => isset($searchPerson)?$searchPerson:null]) ?>

	<?php foreach ($persons as $person) : ?>
		<option value="<?= $person->id ?>" <?= ($model->person_id == $person->id)?'selected':'' ?>>
	<?= $person->surname . ' ' . $person->name . ' ' . (is_null($person->secondname)?'':$person->secondname) . ' ' . $person->birthdate ?>
		</option>
	<?php endforeach; ?>
    <?php if (count($persons) == 0) : ?>
    <?php if (isset($model->person_id)) : ?>
		<option value="<?= $model->person_id ?>" selected>
			<?= htmlspecialchars(common\models\Person::getPersonTextRepresentationById($model->person_id)) ?>
		</option>
	<?php endif; ?>
    	<option value=""><?= Yii::t('app', 'Not found') ?></option>
    <?php endif; ?>

