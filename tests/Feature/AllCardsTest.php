<?php

namespace Tests\Feature;

use App\Models\Answer;
use App\Models\Flashcard;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AllCardsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function a_player_can_see_all_cards()
    {
        $cards = Flashcard::factory(2)->has(Answer::factory())->create();

        $firstCard = $cards->first();
        $secondCard = $cards->get(1);

        $this->artisan('flashcard:all')
            ->expectsOutput('Here is a list of all your flashcards.')
            ->expectsTable(['ID', 'Question', 'Answer'], [
                [$firstCard->id, $firstCard->question, $firstCard->answer->text],
                [$secondCard->id, $secondCard->question, $secondCard->answer->text]
            ])
            ->assertSuccessful();
    }

    /**
     * @test
     */
    public function a_player_gets_notified_when_there_are_no_cards()
    {
        $this->artisan('flashcard:all')
            ->expectsOutput('Nothing to see here yet!')
            ->assertSuccessful();
    }
}
