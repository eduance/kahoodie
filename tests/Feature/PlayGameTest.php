<?php

namespace Tests\Feature;

use App\Kahoodie\Game;
use App\Kahoodie\Kahoodie;
use Domain\Flashcard\Models\Answer;
use Domain\Flashcard\Models\Question;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlayGameTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function a_player_can_start_a_game()
    {
        $kahoodie = app(Kahoodie::class);
        $kahoodie->setGame(new Game());

        $unansweredQuestion = Question::factory()->incorrect()
            ->state(['question' => 'Do I look good?'])
            ->has(Answer::factory()->state(['text' => 'Yes, absolutely.']))
            ->create();

        $this->artisan('flashcard:play')
            ->expectsOutput('You are now in interactive mode, press CTRL+C to exit the terminal.')
            ->expectsTable([
                'ID',
                'Question',
                'Status',
            ], [
                [$unansweredQuestion->id, $unansweredQuestion->question, $unansweredQuestion->status->value],
            ])
            ->expectsQuestion('What question would you like to practice?', $unansweredQuestion->id)
            ->expectsQuestion($unansweredQuestion->question, $unansweredQuestion->answer->text)
            ->expectsConfirmation('Want to have another go?', 'no')
            ->expectsOutput('Thanks for playing!')
            ->assertSuccessful();
    }

    /**
     * @test
     */
    public function a_player_can_not_start_a_game_when_he_finished_all_questions()
    {
        $kahoodie = app(Kahoodie::class);
        $kahoodie->setGame(new Game());

        $correctQuestion = Question::factory()->correct()->state(['question' => 'Studocu'])->create();

        $this->artisan('flashcard:play')
            ->expectsOutput('Congratulations! You finished all the questions.')
            ->assertSuccessful();
    }

    /**
     * @test
     */
    public function a_player_can_not_start_a_game_when_there_are_no_rows()
    {
        $kahoodie = app(Kahoodie::class);
        $kahoodie->setGame(new Game());

        $this->artisan('flashcard:play')
            ->expectsOutput('Nothing to see here.')
            ->assertSuccessful();
    }
}
