<?php

namespace Domain\Flashcard\ViewModels;

use Domain\Flashcard\DataTransferObjects\QuestionData;
use Domain\Flashcard\Models\Question;

class GetAllCardsViewModel
{
    /**
     * Get all the questions with their corresponding answers.
     *
     * @return array
     */
    public function questionsWithAnswers()
    {
        return QuestionData::collection(Question::all())
            ->include('answer')
            ->except('status', 'id')
            ->toArray();
    }

    /**
     * Get the total question count.
     *
     * @return mixed
     */
    public function questionCount()
    {
        return Question::count();
    }
}
