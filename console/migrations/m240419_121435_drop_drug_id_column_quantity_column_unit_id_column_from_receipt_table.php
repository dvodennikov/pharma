<?php

use yii\db\Migration;

/**
 * Handles dropping columns from table `{{%receipt}}`.
 */
class m240419_121435_drop_drug_id_column_quantity_column_unit_id_column_from_receipt_table extends Migration
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

		// drops column `drug_id`
		$this->dropColumn('{{%receipt}}', 'drug_id');
		
		// drops column `quantity`
		$this->dropColumn('{{%receipt}}', 'quantity');
		
		//drops column `unit_id`
		$this->dropColumn('{{%receipt}}', 'unit_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
		// add column `drug_id`
		$this->addColumn('{{%receipt}}', 'drug_id', $this->integer(11)->notNull());
		
		// add column `quantity`
		$this->addColumn('{{%receipt}}', 'quantity', $this->integer(6)->notNull()->defaultValue(1));
		
		// add column `unit_id`
		$this->addColumn('{{%receipt}}', 'unit_id', $this->integer(11)->notNull());
		
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
}
