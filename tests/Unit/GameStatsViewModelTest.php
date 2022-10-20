<?php

namespace Tests\Unit;

use Domain\Flashcard\Models\Attempt;
use Domain\Flashcard\Models\Question;
use Domain\Flashcard\ViewModels\GetStatsViewModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\App;
use Tests\TestCase;

class GameStatsViewModelTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_can_calculate_the_questions_with_an_answer()
    {
        $unanswered = Question::factory(5)->create();
        $incorrect = Question::factory(10)->has(Attempt::factory()->incorrect())->create();
        $correct = Question::factory(4)->has(Attempt::factory()->correct())->create();

        $viewModel = App::make(GetStatsViewModel::class);

        $this->assertEquals('74%', $viewModel->relativeQuestionsWithAnswer());
    }

    /**
     * @test
     */
    public function it_can_calculate_the_questions_with_a_correct_answer()
    {
        $unanswered = Question::factory(5)->create();
        $incorrect = Question::factory(10)->has(Attempt::factory()->incorrect())->create();
        $correct = Question::factory(4)->has(Attempt::factory()->correct())->create();

        $viewModel = App::make(GetStatsViewModel::class);

        $this->assertEquals('21%', $viewModel->relativeQuestionsWithCorrectAnswer());
    }
}
