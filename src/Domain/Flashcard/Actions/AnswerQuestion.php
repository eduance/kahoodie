<?php

namespace Domain\Flashcard\Actions;

use App\Events\ProcessQuestion;
use Domain\Flashcard\DataTransferObjects\QuestionData;
use Domain\Flashcard\Enums\QuestionStatus;
use Domain\Flashcard\Models\Attempt;
use Domain\Flashcard\Models\Question;
use Exception;

class AnswerQuestion
{
    /**
     * Answer a question with the given answer.
     *
     * @param QuestionData $question
     * @param string $answer
     * @return bool
     *
     * @throws Exception
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

        $question = Question::findOr($question['id'],  fn () => throw new Exception("Question not found"));
        ProcessQuestion::dispatch($question, $correct ? QuestionStatus::Correct : QuestionStatus::Incorrect);

        return $correct;
    }
}
