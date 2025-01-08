<?php

namespace common\modules\restapi\v1\models;

class Document extends \common\models\Document
{
	/**
	 * {@inheritdoc}
	 */
	/*public function fields()
	{
		return [
			'id',
			'serial',
			'surname',
			'name',
			'secondname'
		];
	}*/
	
	/**
	 * {@inheritdoc}
	 */
	public function extraFields()
	{
		return [
			'documentType' => 'documentType',
			'person'       => 'person',
		];
	}
}
