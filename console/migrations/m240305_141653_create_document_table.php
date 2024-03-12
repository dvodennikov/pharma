<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%document}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%document_type}}`
 */
class m240305_141653_create_document_table extends Migration
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
        $this->createTable('{{%document}}', [
            'id' => $this->primaryKey(),
            'document_type' => $this->integer(11)->notNull(),
            'serial' => $this->string(10)->defaultValue(null),
            'number' => $this->integer(16)->notNull(),
            'issue_date' => $this->date()->notNull(),
            'issuer' => $this->string(255)->notNull(),
            'expire_date' => $this->date()->defaultValue(null),
            'custom_fields' => $this->json()->defaultValue(null),
        ]);

        // creates index for column `document_type`
        $this->createIndex(
            '{{%idx-document-document_type}}',
            '{{%document}}',
            'document_type'
        );

        // add foreign key for table `{{%document_type}}`
        $this->addForeignKey(
            '{{%fk-document-document_type}}',
            '{{%document}}',
            'document_type',
            '{{%document_type}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%document_type}}`
        $this->dropForeignKey(
            '{{%fk-document-document_type}}',
            '{{%document}}'
        );

        // drops index for column `document_type`
        $this->dropIndex(
            '{{%idx-document-document_type}}',
            '{{%document}}'
        );

        $this->dropTable('{{%document}}');
    }
}
