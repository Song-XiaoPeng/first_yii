<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user_backend`.
 */
class m170729_064816_create_user_backend_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        /*$this->createTable('user_backend', [
            'id' => $this->integer(),
            'username' => $this->string(255)->notNull()->unique(),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string(255)->notNull(),
            'email' => $this->string(255)->notNull()->unique(),

            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
        ]);*/

//        $this->alterColumn('user_backend', 'id', 'int(11) auto_increment COMMENT "ID"');
        $this->alterColumn('user_backend', 'id', 'int(11) auto_increment COMMENT "ID"');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
//        $this->dropTable('user_backend');
        $this->alterColumn('user_backend', 'id', 'int(11) auto_increment COMMENT "ID"');
    }

}
