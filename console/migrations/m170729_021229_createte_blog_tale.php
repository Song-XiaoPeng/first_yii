<?php

use yii\db\Migration;

class m170729_021229_createte_blog_tale extends Migration
{
    public function safeUp()
    {
        $this->createTable('blog',[
            'id'=>$this->primaryKey(),
            'title'=>$this->string(100)->notNull()->defaultValue(''),
            'content'=>$this->text(),
            'create_time'=>$this->datetime(),
        ]);
    }

    public function safeDown()
    {
//        echo "m170729_021229_createte_blog_tale cannot be reverted.\n";

        $this->dropTable('blog');


//        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170729_021229_createte_blog_tale cannot be reverted.\n";

        return false;
    }
    */
}
