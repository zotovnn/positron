<?php

use yii\db\Migration;

/**
 * Class m200803_031840_add_table_feedback
 */
class m200803_031840_add_table_feedback extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%feedback}}', [
            'id' => $this->bigPrimaryKey(),
            'name' => $this->string(),
            'email' => $this->string()->notNull(),
            'body' => $this->text()->notNull(),
            'phone' => $this->string(),
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');

        $this->createIndex('id', '{{%feedback}}', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200803_031840_add_table_feedback cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200803_031840_add_table_feedback cannot be reverted.\n";

        return false;
    }
    */
}
