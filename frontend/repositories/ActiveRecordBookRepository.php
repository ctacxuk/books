<?php

namespace frontend\repositories;

use frontend\models\Author;
use frontend\models\Book;
use frontend\models\BookAuthor;
use yii\helpers\ArrayHelper;

class ActiveRecordBookRepository implements BookRepositoryInterface
{
    /**
     * @param Author[] $authors
     */
    #[\Override] public function upsert(Book $book, array $authors): int
    {
        $book->save(false);
        BookAuthor::deleteAll(['book_id' => $book->id]);
        foreach ($authors as $author) {
            $bookAuthor = new BookAuthor();
            $bookAuthor->author_id = $author->id;
            $bookAuthor->book_id = $book->id;
            $bookAuthor->save();
        }

        return $book->id;
    }


    #[\Override] public function findOrCreateAuthors(array $authors): array
    {
        $authorModels = Author::find()->where(['in', 'name', $authors])->all();
        $authorNames = ArrayHelper::getColumn($authorModels, 'name');
        foreach ($authors as $author) {
            if (!in_array($author, $authorNames)) {
                $newAuthor = new Author();
                $newAuthor->name = $author;
                if ($newAuthor->save()) {
                    $authorModels[] = $newAuthor;
                }

            }
        }

        return $authorModels;
    }

    #[\Override] public function findTop10Authors(int $year): array
    {
        return Author::find()
            ->select(['authors.name', 'books.year', 'COUNT(books.id) as cnt'])
            ->leftJoin('book_authors', 'book_authors.author_id = authors.id')
            ->leftJoin('books', 'book_authors.book_id = books.id')
            ->where(['books.year' => $year])
            ->groupBy('authors.id')
            ->orderBy(['cnt' => SORT_DESC])
            ->limit(10)
            ->asArray()->all();
    }
}