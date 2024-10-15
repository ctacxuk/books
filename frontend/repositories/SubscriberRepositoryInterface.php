<?php

namespace frontend\repositories;

use frontend\models\AuthorSubscriber;

interface SubscriberRepositoryInterface
{
    public function add(AuthorSubscriber $subscriber): void;
    public function findSubscriberByAuthorIds(array $authorIds): iterable;
}