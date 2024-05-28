<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%document}}`.
 */
class m240527_134635_add_updated_at_and_updated_by_column_to_document_table extends Migration
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
        $this->addColumn('{{%document}}', 'updated_at', $this->integer(11)->defaultValue(null));
        $this->addColumn('{{%document}}', 'updated_by', $this->integer(11)->defaultValue(null));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%document}}', 'updated_at');
        $this->dropColumn('{{%document}}', 'updated_by');
    }
}
