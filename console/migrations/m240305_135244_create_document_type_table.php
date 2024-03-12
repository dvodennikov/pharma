<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%document_type}}`.
 */
class m240305_135244_create_document_type_table extends Migration
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
        $this->createTable('{{%document_type}}', [
            'id' => $this->primaryKey(),
            'title' => $this->varchar(255)->unique(),
            'custom_fields' => $this->json()->default(null),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%document_type}}');
    }
}
