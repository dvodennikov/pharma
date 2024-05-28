<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%receipt}}`.
 */
class m240527_135027_add_updated_at_and_updated_by_column_to_receipt_table extends Migration
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
        $this->addColumn('{{%receipt}}', 'updated_at', $this->integer(11)->defaultValue(null));
        $this->addColumn('{{%receipt}}', 'updated_by', $this->integer(11)->defaultValue(null));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%receipt}}', 'updated_at');
        $this->dropColumn('{{%receipt}}', 'updated_by');
    }
}
