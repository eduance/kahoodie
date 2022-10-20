<?php

namespace Domain\Flashcard\Actions;

use Domain\Flashcard\DataTransferObjects\QuestionData;
use Domain\Flashcard\Enums\QuestionStatus;
use Domain\Flashcard\Models\Attempt;
use Domain\Flashcard\Models\Question;

class AnswerQuestion
{
    /**
     * Answer a question with the given answer.
     *
     * @param QuestionData $question
     * @param string $answer
     * @return bool
     */
    public function handle(QuestionData $question, string $answer): bool
    {
        $correct = false;
        $question = $question->include('answer')->toArray();

        if ($question['answer'] === $answer) {
            $correct = true;
        }

        Attempt::create([
            'correct' => $correct,
            'question_id' => $question['id'],
        ]);

        $question = Question::find($question['id']);
        $question->update([
            'status' => $correct ? QuestionStatus::Correct : QuestionStatus::Incorrect
        ]);

        return $correct;
    }
}
