<?php

namespace frontend\repositories;

use frontend\models\Book;

interface BookRepositoryInterface
{
    public function upsert(Book $book, array $authors): int;

    public function findOrCreateAuthors(array $authors): array;

    public function findTop10Authors(int $year): array;
}