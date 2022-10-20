<?php

namespace Domain\Flashcard\ViewModels;

use App\ValueObjects\Percentage;
use Domain\Flashcard\DataTransferObjects\QuestionData;
use Domain\Flashcard\Models\Question;
use Spatie\LaravelData\CursorPaginatedDataCollection;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\PaginatedDataCollection;

class GetGameViewModel
{
    /**
     * Get the total questions.
     *
     * @return CursorPaginatedDataCollection|DataCollection|PaginatedDataCollection
     */
    public function questions(): PaginatedDataCollection|CursorPaginatedDataCollection|DataCollection
    {
        return QuestionData::collection(Question::all());
    }

    /**
     * Get the total percentage of completed flashcards.
     *
     * @return Percentage
     */
    public function completionRate(): Percentage
    {
        return Percentage::from(
            numerator: Question::whereCorrect()->count(),
            denominator: Question::count()
        );
    }
}
