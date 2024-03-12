<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%receipt}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%person}}`
 * - `{{%drug}}`
 * - `{{%unit}}`
 */
class m240306_222853_create_receipt_table extends Migration
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
        $this->createTable('{{%receipt}}', [
            'id' => $this->primaryKey(),
            'number' => $this->string(10)->defaultValue(null),
            'person_id' => $this->integer(11)->notNull(),
            'drug_id' => $this->integer(11)->notNull(),
            'quantity' => $this->integer(6)->notNull()->defaultValue(1),
            'unit_id' => $this->integer(11)->notNull(),
        ]);

        // creates index for column `person_id`
        $this->createIndex(
            '{{%idx-receipt-person_id}}',
            '{{%receipt}}',
            'person_id'
        );

        // add foreign key for table `{{%person}}`
        $this->addForeignKey(
            '{{%fk-receipt-person_id}}',
            '{{%receipt}}',
            'person_id',
            '{{%person}}',
            'id',
            'CASCADE'
        );

        // creates index for column `drug_id`
        $this->createIndex(
            '{{%idx-receipt-drug_id}}',
            '{{%receipt}}',
            'drug_id'
        );

        // add foreign key for table `{{%drug}}`
        $this->addForeignKey(
            '{{%fk-receipt-drug_id}}',
            '{{%receipt}}',
            'drug_id',
            '{{%drug}}',
            'id',
            'CASCADE'
        );

        // creates index for column `unit_id`
        $this->createIndex(
            '{{%idx-receipt-unit_id}}',
            '{{%receipt}}',
            'unit_id'
        );

        // add foreign key for table `{{%unit}}`
        $this->addForeignKey(
            '{{%fk-receipt-unit_id}}',
            '{{%receipt}}',
            'unit_id',
            '{{%unit}}',
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
            '{{%fk-receipt-person_id}}',
            '{{%receipt}}'
        );

        // drops index for column `person_id`
        $this->dropIndex(
            '{{%idx-receipt-person_id}}',
            '{{%receipt}}'
        );

        // drops foreign key for table `{{%drug}}`
        $this->dropForeignKey(
            '{{%fk-receipt-drug_id}}',
            '{{%receipt}}'
        );

        // drops index for column `drug_id`
        $this->dropIndex(
            '{{%idx-receipt-drug_id}}',
            '{{%receipt}}'
        );

        // drops foreign key for table `{{%unit}}`
        $this->dropForeignKey(
            '{{%fk-receipt-unit_id}}',
            '{{%receipt}}'
        );

        // drops index for column `unit_id`
        $this->dropIndex(
            '{{%idx-receipt-unit_id}}',
            '{{%receipt}}'
        );

        $this->dropTable('{{%receipt}}');
    }
}
