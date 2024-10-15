<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%book_subscribers}}`.
 */
class m241015_042752_create_author_subscribers_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%author_subscribers}}', [
            'id' => $this->primaryKey(),
            'phone' => $this->string(),
            'author_id' => $this->integer(),
        ]);

        $this->addForeignKey(
            'fk-subscribers-author_id',
            'author_subscribers',
            'author_id',
            'authors',
            'id',
            'RESTRICT'
        );

        $this->createIndex(
            'uidx-authors-phone-author_id',
            'author_subscribers',
            ['phone, author_id'],
            true
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%author_subscribers}}');
    }
}
