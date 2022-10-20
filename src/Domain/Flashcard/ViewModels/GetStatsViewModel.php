<?php

namespace Domain\Flashcard\ViewModels;

use App\ValueObjects\Percentage;
use Domain\Flashcard\Models\Question;

class GetStatsViewModel
{
    /**
     * Total question count.
     *
     * @return int
     */
    public function totalQuestionCount()
    {
        return Question::count();
    }

    /**
     * Get the relative amount of questions with an answer.
     *
     * @return Percentage
     */
    public function relativeQuestionsWithAnswer()
    {
        return Percentage::from(
            numerator: Question::has('attempts')->count(),
            denominator: Question::count(),
        );
    }

    /**
     * Get the percentage of questions with a correct answer.
     *
     * @return Percentage
     */
    public function relativeQuestionsWithCorrectAnswer()
    {
        return Percentage::from(
            numerator: Question::whereRelation('attempts', 'correct', true)->count(),
            denominator: Question::count(),
        );
    }
}
