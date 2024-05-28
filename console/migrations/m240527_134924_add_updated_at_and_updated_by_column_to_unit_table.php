<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%unit}}`.
 */
class m240527_134924_add_updated_at_and_updated_by_column_to_unit_table extends Migration
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
        $this->addColumn('{{%unit}}', 'updated_at', $this->integer(11)->defaultValue(null));
        $this->addColumn('{{%unit}}', 'updated_by', $this->integer(11)->defaultValue(null));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%unit}}', 'updated_at');
        $this->dropColumn('{{%unit}}', 'updated_by');
    }
}
