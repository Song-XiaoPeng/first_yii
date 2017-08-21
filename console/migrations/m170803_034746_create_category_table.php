<?php

use yii\db\Migration;

/**
 * Handles the creation of table `category`.
 */
class m170803_034746_create_category_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('category', [
            'id' => $this->primaryKey(),
            'name' => $this->string(20)->notNull()->comment('栏目名')->defaultValue(''),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('category');
    }
}
