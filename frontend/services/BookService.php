<?php

namespace frontend\services;


use frontend\dtos\BookDto;
use frontend\dtos\SubscribeDto;
use frontend\models\AuthorSubscriber;
use frontend\models\Book;
use frontend\repositories\BookRepositoryInterface;
use frontend\repositories\SubscriberRepositoryInterface;
use yii\base\Event;

class BookService
{
    public function __construct(
        private BookRepositoryInterface $bookRepository,
        private SubscriberRepositoryInterface $subscriberRepository,
    )
    {
    }


    public function createBook(BookDto $bookDto, array $authors): int
    {
        $book = new Book();
        $book->title = $bookDto->title;
        $book->description = $bookDto->description;
        $book->isbn = $bookDto->isbn;
        $book->image = $bookDto->image;
        $book->year = $bookDto->year;

        $authors = $this->bookRepository->findOrCreateAuthors($authors);

        $id = $this->bookRepository->upsert($book, $authors);

        Event::trigger(Book::class, Book::EVENT_NEW_BOOK);

        return $id;
    }


    public function updateBook(BookDto $bookDto, array $authors): void
    {

        $book = Book::findOne($bookDto->id);
        $book->title = $bookDto->title;
        $book->description = $bookDto->description;
        $book->isbn = $bookDto->isbn;
        $book->image = $bookDto->image;
        $book->year = $bookDto->year;

        $authors = $this->bookRepository->findOrCreateAuthors($authors);

        $this->bookRepository->upsert($book, $authors);
    }

    public function subscribeBook(SubscribeDto $dto): void
    {
        $subscribe = new AuthorSubscriber();
        $subscribe->author_id = $dto->authorId;
        $subscribe->phone = $dto->phone;

        $this->subscriberRepository->add($subscribe);
    }
}