<?php

namespace Tests\Feature;

use Domain\Flashcard\Enums\QuestionStatus;
use Domain\Flashcard\Models\Answer;
use Domain\Flashcard\Models\Attempt;
use Domain\Flashcard\Models\Question;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResetTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function a_player_can_reset_their_game()
    {
        Question::factory()
            ->correct()
            ->has(Answer::factory())
            ->has(Attempt::factory(2)->incorrect())
            ->has(Attempt::factory(3)->correct())
            ->create();

        Question::factory(4)->incorrect()->has(Answer::factory())->create();
        Question::factory(2)->correct()->has(Answer::factory())->create();

        $this->artisan('flashcard:reset')
             ->expectsOutput('We resetted your progress. Goodluck!')
             ->expectsQuestion('Type anything to continue', 'p');

        $this->assertEquals(0, Question::has('attempts')->count());
    }
}
