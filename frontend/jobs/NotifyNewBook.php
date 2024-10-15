<?php

namespace frontend\jobs;

use frontend\components\SmsNotificationSenderInterface;
use frontend\models\AuthorSubscriber;
use frontend\repositories\SubscriberRepositoryInterface;
use Yii;
use yii\base\BaseObject;

class NotifyNewBook extends BaseObject implements \yii\queue\JobInterface
{
    public array $authorIds;


    #[\Override] public function execute($queue)
    {
        $repository = Yii::createObject(SubscriberRepositoryInterface::class);
        $smsService = Yii::createObject(SmsNotificationSenderInterface::class);
        /** @var AuthorSubscriber $subscriber */
        foreach ($repository->findSubscriberByAuthorIds($this->authorIds) as $subscriber) {
            $smsService->sendNotify('Новая книга такого автора', $subscriber->phone);
        }
    }
}