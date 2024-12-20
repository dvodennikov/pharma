<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%unit}}`.
 */
class m240306_125024_create_unit_table extends Migration
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
        $this->createTable('{{%unit}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->notNull(),
            'description' => $this->string(4096)->defaultValue(null),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%unit}}');
    }
}
