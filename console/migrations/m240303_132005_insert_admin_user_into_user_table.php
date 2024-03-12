<?php

use yii\db\Migration;

/**
 * Class m240303_132005_insert_admin_user_into_user_table
 */
class m240303_132005_insert_admin_user_into_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		$this->insert('{{%user}}', [ 'username' => 'admin',
								     'password_hash' => \Yii::$app->security->generatePasswordHash('admin'),
								     'auth_key' => \Yii::$app->security->generateRandomString(),
								     'email' => 'admin@localhost',
								     'created_at' => time(),
								     'updated_at' => time(),
								   ]);
		
		return true;
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('{{%user}}', [ 'username' => 'admin' ]);

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240303_132005_insert_admin_user_into_user_table cannot be reverted.\n";

        return false;
    }
    */
}
