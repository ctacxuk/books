<?php

namespace frontend\dtos;


readonly class SubscribeDto
{
    public function __construct(
        public int $authorId,
        public string $phone,
    )
    {
    }
}