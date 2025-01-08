<?php

namespace common\modules\restapi\v1\models;

class Drug extends \common\models\Drug
{
	/**
	 * {@inheritdoc}
	 */
	public function extraFields()
	{
		return [
			'measuryUnit' => 'measuryUnit',
		];
	}
}
