<?php

namespace frontend\components;

class SmsPilotNotificationSenderInterface implements SmsNotificationSenderInterface
{

    #[\Override] public function sendNotify(string $text, string $phone): void
    {
        // TODO: Implement sendNotify() method.
        // Запрос запрос к апи сервиса
    }
}