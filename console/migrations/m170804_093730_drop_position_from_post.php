<?php

use yii\db\Migration;

class m170804_093730_drop_position_from_post extends Migration
{
    public function safeUp()
    {
        $this->dropColumn();
    }

    public function safeDown()
    {
        echo "m170804_093730_drop_position_from_post cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170804_093730_drop_position_from_post cannot be reverted.\n";

        return false;
    }
    */
}
