<?php

use yii\db\Migration;

/**
 * Class m200727_231940_add_table_books
 */
class m200727_231940_add_table_books extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%books}}', [
            'id' => $this->bigPrimaryKey(),
            'isbn' => $this->bigInteger()->notNull(),
            'title' => $this->string(),
            'page_count' => $this->integer()->defaultValue(0),
            'published_date' => $this->date(),
            'thumbnail_url' => $this->string(),
            'short_description' => $this->text(),
            'long_description' => $this->text(),
            'status' => $this->string(),
            'authors' => $this->text(),
            'categories' => $this->text(),
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');

        $this->createIndex('id', '{{%books}}', 'id');
        $this->createIndex('isbn', '{{%books}}', 'isbn');
        $this->createIndex('title', '{{%books}}', 'title');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200727_231940_add_table_books cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200727_231940_add_table_books cannot be reverted.\n";

        return false;
    }
    */
}
