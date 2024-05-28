<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%document_type}}`.
 */
class m240527_134212_add_updated_at_and_updated_by_column_to_document_type_table extends Migration
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
        $this->addColumn('{{%document_type}}', 'updated_at', $this->integer(11)->defaultValue(null));
        $this->addColumn('{{%document_type}}', 'updated_by', $this->integer(11)->defaultValue(null));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%document_type}}', 'updated_at');
        $this->dropColumn('{{%document_type}}', 'updated_by');
    }
}
