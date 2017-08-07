<?php

use yii\db\Migration;

/**
 * Handles the creation of table `blog`.
 */
class m170804_092347_create_blog_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('blog', [
            'id' => $this->primaryKey(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('blog');
    }
}
