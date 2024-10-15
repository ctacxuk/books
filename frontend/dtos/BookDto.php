<?php

namespace frontend\dtos;

use frontend\models\Book;

readonly class BookDto
{
    public function __construct(
        public ?int $id = null,
        public string $title,
        public string $description,
        public string $isbn,
        public string $year,
        public ?string $image = null
    )
    {
    }


    public static function fromActiveRecord(Book $book): self
    {
        return new self(
            id: $book->id,
            title: $book->title,
            description: $book->description,
            isbn: $book->isbn,
            image: $book->image,
            year: $book->year
        );
    }
}