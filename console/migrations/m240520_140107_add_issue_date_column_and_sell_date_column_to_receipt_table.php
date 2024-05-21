<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%receipt}}`.
 */
class m240520_140107_add_issue_date_column_and_sell_date_column_to_receipt_table extends Migration
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
        $this->addColumn('{{%receipt}}', 'issue_date', $this->date()->defaultValue(null));
        $this->addColumn('{{%receipt}}', 'sell_date', $this->date()->defaultValue(null));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%receipt}}', 'issue_date');
        $this->dropColumn('{{%receipt}}', 'sell_date');
    }
}
