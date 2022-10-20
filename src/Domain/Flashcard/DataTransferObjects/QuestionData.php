<?php

namespace Domain\Flashcard\DataTransferObjects;

use Domain\Flashcard\Enums\QuestionStatus;
use Domain\Flashcard\Models\Question;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Lazy;

class QuestionData extends Data
{
    public function __construct(
        public int $id,
        public string $question,
        #[WithCast(EnumCast::class)]
        public QuestionStatus $status,
        #[DataCollectionOf(AnswerData::class)]
        public Lazy|DataCollection $answer
    )
    {
    }

    public static function fromModel(Question $question)
    {
        return new self(
            $question->id,
            $question->question,
            $question->status,
            Lazy::create(fn () => $question->answer->text)
        );
    }
}
