<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%drug}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%unit}}`
 */
class m240306_130847_create_drug_table extends Migration
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
        $this->createTable('{{%drug}}', [
            'id' => $this->primaryKey(),
            'title' => $this->varchar(255)->notNull(),
            'description' => $this->varchar(4096)->default(null),
            'measury' => $this->int(11)->notNull(),
            'measury_unit' => $this->int(11)->notNull(),
        ]);

        // creates index for column `measury_unit`
        $this->createIndex(
            '{{%idx-drug-measury_unit}}',
            '{{%drug}}',
            'measury_unit'
        );

        // add foreign key for table `{{%unit}}`
        $this->addForeignKey(
            '{{%fk-drug-measury_unit}}',
            '{{%drug}}',
            'measury_unit',
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
        // drops foreign key for table `{{%unit}}`
        $this->dropForeignKey(
            '{{%fk-drug-measury_unit}}',
            '{{%drug}}'
        );

        // drops index for column `measury_unit`
        $this->dropIndex(
            '{{%idx-drug-measury_unit}}',
            '{{%drug}}'
        );

        $this->dropTable('{{%drug}}');
    }
}
