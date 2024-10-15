<?php

namespace frontend\components;

interface SmsNotificationSenderInterface
{
    public function sendNotify(string $text, string $phone): void;
}