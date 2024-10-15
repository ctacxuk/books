<?php

namespace frontend\events;

use frontend\jobs\NotifyNewBook;
use frontend\models\Book;
use Yii;
use yii\helpers\ArrayHelper;

class NewBookHandler
{
    public static function handle(\yii\base\Event $event)
    {
        /** @var Book $book */
        $book = $event->sender;

        if ($book && $book->authors) {
            Yii::$app->queue->push(new NotifyNewBook([
                'authorIds' => ArrayHelper::getColumn($book->authors, 'id'),
            ]));
        }
    }
}