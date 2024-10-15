<?php

namespace frontend\repositories;

use frontend\models\Author;
use frontend\models\AuthorSubscriber;
use frontend\models\Book;
use frontend\models\BookAuthor;
use yii\helpers\ArrayHelper;

class ActiveRecordSubscriberRepository implements SubscriberRepositoryInterface
{

    #[\Override] public function add(AuthorSubscriber $subscriber): void
    {
        $subscriber->save();
    }

    #[\Override] public function findSubscriberByAuthorIds(array $authorIds): iterable
    {
        return AuthorSubscriber::find()->where(['in', 'author_id', $authorIds])->each();
    }
}