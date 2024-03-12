<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%person}}`.
 */
class m240304_125557_create_person_table extends Migration
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
        $this->createTable('{{%person}}', [
            'id' => $this->primaryKey(),
            'surname' => $this->string(255)->notNull(),
            'name' => $this->string(255)->notNull(),
            'secondname' => $this->string(255)->defaultValue(null),
            'birthdate' => $this->date()->notnull(),
            'snils' => $this->integer(11)->notNull(),
            'polis' => $this->integer(14)->defaultValue(null),
            'address' => $this->string(1024)->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%person}}');
    }
}
