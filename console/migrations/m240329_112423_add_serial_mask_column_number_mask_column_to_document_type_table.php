<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%document_type}}`.
 */
class m240329_112423_add_serial_mask_column_number_mask_column_to_document_type_table extends Migration
{
	/**
     * {@inheritdoc}
     */
    public function init()
    {
		$this->db = 'dbdata';
		
		parent::init();
	}
	
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%document_type}}', 'serial_mask', $this->string(255)->defaultValue(null));
        $this->addColumn('{{%document_type}}', 'number_mask', $this->string(255)->defaultValue(null));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%document_type}}', 'serial_mask');
        $this->dropColumn('{{%document_type}}', 'number_mask');
    }
}
