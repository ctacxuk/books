<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%books}}`.
 */
class m241014_133527_create_books_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%books}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'description' => $this->text()->notNull(),
            'isbn' => $this->string()->notNull(),
            'image' => $this->string(),
            'year' => $this->integer()->notNull(),
        ]);


        $this->createIndex(
            'idx-books-year',
            'books',
            'year'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%books}}');
    }
}
