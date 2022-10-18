<?php

namespace Tests\Feature;

use App\Models\Answer;
use App\Models\Flashcard;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlayFlashcardGameTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function a_player_can_start_a_game()
    {
        // Arrange
        Flashcard::factory()->has(Answer::factory()->state(['text' => 'Brandon']))->create(
            ['question' => 'Who made Kahoodie?']
        );

        // Act

        // So, where do we register our attempt?
        // We can create a fresh table named attempts and save all our attempts into it?

        // flashcard | answer       | attempts
        // id        | id           | id
        // question  | text         | question_id
        // answer    | flashcard_id | status

        // In our case, we could get away with saving both our question and answer in the flashcard table.
        // There is no real need yet for a separate model and because we only have one-to-one information
        // that's going to be the refactor so we can end up doing less joins! :-)

        // The wishful syntax would be:
        // Flashcard::find(10)->tryAnswer('Brandon');

        // We can just store our attempts in the database, together with the corresponding status.
        // In order to avoid having to recalculate it every single time, clean!

        // Assert that we can see a table listing all questions and their practice status.

        $this->artisan('flashcard:play')
            ->expectsTable(
                [
                    'id',
                    'question',
                    'status'
                ],
                [

                ]
            );

        // Assert
    }
}
