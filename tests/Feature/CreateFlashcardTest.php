<?php

namespace Tests\Feature;

use Domain\Flashcard\Models\Answer;
use Domain\Flashcard\Models\Question;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateFlashcardTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function a_player_can_create_a_question()
    {
        $this->artisan('flashcard:create')
            ->expectsQuestion('What question would you like the players to ask?', $question = 'What time is it?')
            ->expectsQuestion('What is the corresponding answer?', $answer = 'A great time to be alive.')
            ->expectsOutput('Congratulations! Your question has succesfully been added to Kahoodie.')
            ->expectsQuestion('Type anything to continue', 'click')
            ->assertSuccessful();

        $this->assertInstanceOf(Question::class, $question = Question::whereQuestion($question)->first());
        $this->assertInstanceOf(Answer::class, $answer = Answer::whereText($answer)->first());
        $this->assertEquals($question->answer->id, $answer->id);
    }

    /**
     * @test
     * @dataProvider provideValidationData
     */
    public function a_player_receives_error_upon_submitting_invalid_data(
        array $validationData,
        array $error,
    ) {
        Question::factory()->create(['question' => 'exists']);
        Answer::factory()->create(['text' => 'exists']);

        $this->artisan('flashcard:create')
            ->expectsQuestion('What question would you like the players to ask?', $validationData['question'])
            ->expectsQuestion('What is the corresponding answer?', $validationData['answer'])
            ->expectsOutput($error['question'])
            ->expectsOutput($error['answer'])
            ->assertFailed();
    }

    public function provideValidationData()
    {
        return [
            [
                [
                    'question' => '',
                    'answer' => ''
                ],
                'error' =>
                    [
                        'question' => 'The question field is required.',
                        'answer' => 'The answer field is required.'
                    ]
            ],
            [
                [
                    'question' => 'exists',
                    'answer' => 'exists'
                ],
                'error' =>
                    [
                        'question' => 'The question already exists.',
                        'answer' => 'The answer already exists.',
                    ]
            ],
        ];
    }
}
