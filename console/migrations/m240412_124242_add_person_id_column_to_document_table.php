<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%document}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%person}}`
 */
class m240412_124242_add_person_id_column_to_document_table extends Migration
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
        $this->addColumn('{{%document}}', 'person_id', $this->integer(11)->defaultValue(null));

        // creates index for column `person_id`
        $this->createIndex(
            '{{%idx-document-person_id}}',
            '{{%document}}',
            'person_id'
        );

        // add foreign key for table `{{%person}}`
        $this->addForeignKey(
            '{{%fk-document-person_id}}',
            '{{%document}}',
            'person_id',
            '{{%person}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%person}}`
        $this->dropForeignKey(
            '{{%fk-document-person_id}}',
            '{{%document}}'
        );

        // drops index for column `person_id`
        $this->dropIndex(
            '{{%idx-document-person_id}}',
            '{{%document}}'
        );

        $this->dropColumn('{{%document}}', 'person_id');
    }
}
