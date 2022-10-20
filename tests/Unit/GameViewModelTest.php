<?php

namespace Tests\Unit;

use Domain\Flashcard\Models\Question;
use Domain\Flashcard\ViewModels\GetGameViewModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\App;
use Tests\TestCase;

class GameViewModelTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_can_calculate_the_completion_rate()
    {
        Question::factory()->correct()->create();
        Question::factory()->create();

        $flashcardViewModel = App::make(GetGameViewModel::class);

        $this->assertEquals('50%', $flashcardViewModel->completionRate()->formatted);
    }

    /**
     * @test
     */
    public function it_can_calculate_the_raw_completion_rate()
    {
        Question::factory()->correct()->create();
        Question::factory()->create();

        $flashcardViewModel = App::make(GetGameViewModel::class);

        $this->assertEquals(50, $flashcardViewModel->completionRate()->raw);
    }

    /**
     * @test
     */
    public function it_can_round_the_completion_rate_to_the_highest_number()
    {
        Question::factory()->correct()->create();
        Question::factory()->correct()->create();
        Question::factory()->create();

        $flashcardViewModel = app(GetGameViewModel::class);

        $this->assertEquals('67%', $flashcardViewModel->completionRate()->formatted);
    }
}
