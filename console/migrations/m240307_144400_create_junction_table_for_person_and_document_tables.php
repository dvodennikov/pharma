<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%person_document}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%person}}`
 * - `{{%document}}`
 */
class m240307_144400_create_junction_table_for_person_and_document_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%person_document}}', [
            'person_id' => $this->integer(),
            'document_id' => $this->integer(),
            'PRIMARY KEY(person_id, document_id)',
        ]);

        // creates index for column `person_id`
        $this->createIndex(
            '{{%idx-person_document-person_id}}',
            '{{%person_document}}',
            'person_id'
        );

        // add foreign key for table `{{%person}}`
        $this->addForeignKey(
            '{{%fk-person_document-person_id}}',
            '{{%person_document}}',
            'person_id',
            '{{%person}}',
            'id',
            'CASCADE'
        );

        // creates index for column `document_id`
        $this->createIndex(
            '{{%idx-person_document-document_id}}',
            '{{%person_document}}',
            'document_id'
        );

        // add foreign key for table `{{%document}}`
        $this->addForeignKey(
            '{{%fk-person_document-document_id}}',
            '{{%person_document}}',
            'document_id',
            '{{%document}}',
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
            '{{%fk-person_document-person_id}}',
            '{{%person_document}}'
        );

        // drops index for column `person_id`
        $this->dropIndex(
            '{{%idx-person_document-person_id}}',
            '{{%person_document}}'
        );

        // drops foreign key for table `{{%document}}`
        $this->dropForeignKey(
            '{{%fk-person_document-document_id}}',
            '{{%person_document}}'
        );

        // drops index for column `document_id`
        $this->dropIndex(
            '{{%idx-person_document-document_id}}',
            '{{%person_document}}'
        );

        $this->dropTable('{{%person_document}}');
    }
}
