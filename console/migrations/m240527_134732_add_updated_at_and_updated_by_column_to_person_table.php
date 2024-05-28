<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%person}}`.
 */
class m240527_134732_add_updated_at_and_updated_by_column_to_person_table extends Migration
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
        $this->addColumn('{{%person}}', 'updated_at', $this->integer(11)->defaultValue(null));
        $this->addColumn('{{%person}}', 'updated_by', $this->integer(11)->defaultValue(null));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%person}}', 'updated_at');
        $this->dropColumn('{{%person}}', 'updated_by');
    }
}
