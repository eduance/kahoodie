<?php

namespace Tests\Unit;

use Domain\Flashcard\Models\Question;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuestionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_can_get_the_correct_questions()
    {
        Question::factory(3)->correct()->create();

        $this->assertEquals(3, Question::whereCorrect()->count());
    }
}
