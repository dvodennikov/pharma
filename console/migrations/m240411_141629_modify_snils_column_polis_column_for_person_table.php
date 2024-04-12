<?php

use yii\db\Migration;

/**
 * Class m240411_141629_modify_snils_column_polis_column_for_person_table
 */
class m240411_141629_modify_snils_column_polis_column_for_person_table extends Migration
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
		$this->alterColumn('{{%person}}', 'snils', $this->string(11)->notNull());
		$this->alterColumn('{{%person}}', 'polis', $this->string(14)->defaultValue(null));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
		$this->alterColumn('{{%person}}', 'snils', $this->integer(11)->notNull());
		$this->alterColumn('{{%person}}', 'polis', $this->integer(14)->defaultValue(null));

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240411_141629_modify_snils_column_polis_column_for_person_table cannot be reverted.\n";

        return false;
    }
    */
}
