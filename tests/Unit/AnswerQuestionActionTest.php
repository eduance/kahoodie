<?php

namespace Tests\Unit;

use Domain\Flashcard\Actions\AnswerQuestion;
use Domain\Flashcard\DataTransferObjects\QuestionData;
use Domain\Flashcard\Enums\QuestionStatus;
use Domain\Flashcard\Models\Answer;
use Domain\Flashcard\Models\Question;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AnswerQuestionActionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_takes_a_correct_answer()
    {
        $question = Question::factory()->state(['question' => 'When was Studocu born?'])->has(
            Answer::factory()->state(['text' => 2013])
        )->create();

        $action = new AnswerQuestion();
        $action = $action->handle(QuestionData::from($question), 2013);

        $question->refresh();

        $this->assertTrue($action);
        $this->assertEquals(QuestionStatus::Correct, $question->status);
    }

    /**
     * @test
     */
    public function it_takes_wrong_answers()
    {
        $question = Question::factory()->state(['question' => 'When was Studocu born?'])->has(
            Answer::factory()->state(['text' => 2013])
        )->create();

        $action = new AnswerQuestion();
        $action = $action->handle(QuestionData::from($question), 2023);

        $question->refresh();

        $this->assertFalse($action);
        $this->assertEquals(QuestionStatus::Incorrect, $question->status);
    }
}
