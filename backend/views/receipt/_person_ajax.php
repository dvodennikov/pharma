<?php
/** @var yii\web\View $this */
/** @var common\models\Receipt $model */
/** @var string $searchPerson */

use yii\helpers\Html;

	$persons = \common\models\Person::getPersonsBySurnameNameSecondnameBirthdate(['surname' => isset($searchPerson)?$searchPerson:null]);

	$personsList = ['values' => []];
	if (count($persons) == 0) {
	    if (isset($model->person_id)) {
			$personsList['values'][0] = [
				'value' => $model->person_id,
				'title' => htmlspecialchars(common\models\Person::getPersonTextRepresentationById($model->person_id))
			];
		}
    }

	foreach ($persons as $person) {
		$personsList['values'][] = [
			'value' => $person->id,
			'title' => htmlspecialchars($person->surname . ' ' . $person->name . ' ' . (is_null($person->secondname)?'':$person->secondname) . ' ' . $person->birthdate)
		];
	}

	echo json_encode($personsList);
