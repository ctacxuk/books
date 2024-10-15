<?php

namespace frontend\models;

use frontend\events\NewBookHandler;
use Yii;

/**
 * This is the model class for table "books".
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property string $isbn
 * @property string|null $image
 * @property int $year
 *
 * @property Author[] $authors
 */
class Book extends \yii\db\ActiveRecord
{
    const EVENT_NEW_BOOK = 'newBook';

    public $newAuthors;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'books';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'description', 'isbn', 'year', 'newAuthors'], 'required'],
            [['description'], 'string'],
            [['year'], 'integer'],
            [['title', 'isbn', 'image'], 'string', 'max' => 255],
            ['newAuthors', 'each', 'rule' => ['string']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'description' => 'Description',
            'isbn' => 'Isbn',
            'image' => 'Image',
            'year' => 'Year',
            'newAuthors' => 'Authors',
        ];
    }

    /**
     * Gets query for [[BookAuthors]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAuthors()
    {
        return $this->hasMany(Author::class, ['id' => 'author_id'])->viaTable(BookAuthor::tableName(), ['book_id' => 'id']);
    }


    public function init(){
        $this->on(self::EVENT_NEW_BOOK, [NewBookHandler::class, 'handle']);
        parent::init();
    }
}
