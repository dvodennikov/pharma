<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%document}}`.
 */
class m240329_115308_add_surname_column_name_column_second_name_column_to_document_table extends Migration
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
        $this->addColumn('{{%document}}', 'surname', $this->string(255)->notNull());
        $this->addColumn('{{%document}}', 'name', $this->string(255)->notNull());
        $this->addColumn('{{%document}}', 'second_name', $this->string(255)->defaultValue(null));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%document}}', 'surname');
        $this->dropColumn('{{%document}}', 'name');
        $this->dropColumn('{{%document}}', 'second_name');
    }
}
