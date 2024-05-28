<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%drug}}`.
 */
class m240527_134950_add_updated_at_and_updated_by_column_to_drug_table extends Migration
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
        $this->addColumn('{{%drug}}', 'updated_at', $this->integer(11)->defaultValue(null));
        $this->addColumn('{{%drug}}', 'updated_by', $this->integer(11)->defaultValue(null));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%drug}}', 'updated_at');
        $this->dropColumn('{{%drug}}', 'updated_by');
    }
}
