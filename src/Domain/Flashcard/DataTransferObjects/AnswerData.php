<?php

namespace Domain\Flashcard\DataTransferObjects;

use Spatie\LaravelData\Data;

class AnswerData extends Data
{
    public function __construct(
        public string $text,
    )
    {

    }
}
