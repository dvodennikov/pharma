<?php

namespace common\modules\restapi\v1\models;

class Receipt extends \common\models\Receipt
{
	/**
	 * {@inheritdoc}
	 */
	public function extraFields()
	{
		return [
			'person'           => 'person',
			'receiptDrugs'     => 'receiptDrugs',
			'drugsDescription' => 'drugsDescription',
			'drugsList'        => 'drugsList',
		];
	}
}
