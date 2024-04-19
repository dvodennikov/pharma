<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%receipt_drugs}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%receipt}}`
 * - `{{%drug}}`
 */
class m240419_120816_create_receipt_drugs_table extends Migration
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
        $this->createTable('{{%receipt_drugs}}', [
            'id' => $this->primaryKey(),
            'receipt_id' => $this->integer(11)->notNull(),
            'drug_id' => $this->integer(11)->notNull(),
            'quantity' => $this->integer(1)->notNull()->defaultValue(1),
        ]);

        // creates index for column `receipt_id`
        $this->createIndex(
            '{{%idx-receipt_drugs-receipt_id}}',
            '{{%receipt_drugs}}',
            'receipt_id'
        );

        // add foreign key for table `{{%receipt}}`
        $this->addForeignKey(
            '{{%fk-receipt_drugs-receipt_id}}',
            '{{%receipt_drugs}}',
            'receipt_id',
            '{{%receipt}}',
            'id',
            'CASCADE'
        );

        // creates index for column `drug_id`
        $this->createIndex(
            '{{%idx-receipt_drugs-drug_id}}',
            '{{%receipt_drugs}}',
            'drug_id'
        );

        // add foreign key for table `{{%drug}}`
        $this->addForeignKey(
            '{{%fk-receipt_drugs-drug_id}}',
            '{{%receipt_drugs}}',
            'drug_id',
            '{{%drug}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%receipt}}`
        $this->dropForeignKey(
            '{{%fk-receipt_drugs-receipt_id}}',
            '{{%receipt_drugs}}'
        );

        // drops index for column `receipt_id`
        $this->dropIndex(
            '{{%idx-receipt_drugs-receipt_id}}',
            '{{%receipt_drugs}}'
        );

        // drops foreign key for table `{{%drug}}`
        $this->dropForeignKey(
            '{{%fk-receipt_drugs-drug_id}}',
            '{{%receipt_drugs}}'
        );

        // drops index for column `drug_id`
        $this->dropIndex(
            '{{%idx-receipt_drugs-drug_id}}',
            '{{%receipt_drugs}}'
        );

        $this->dropTable('{{%receipt_drugs}}');
    }
}
