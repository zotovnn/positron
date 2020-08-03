<?php

use yii\db\Migration;

/**
 * Class m200803_023242_add_table_categories
 */
class m200803_023242_add_table_categories extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%categories}}', [
            'id' => $this->bigPrimaryKey(),
            'name' => $this->string()->unique(),
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');

        $this->createIndex('id', '{{%categories}}', 'id');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200803_023242_add_table_categories cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200803_023242_add_table_categories cannot be reverted.\n";

        return false;
    }
    */
}
