<?php

namespace Database\Factories;

use Domain\Flashcard\Enums\QuestionStatus;
use Domain\Flashcard\Models\Question;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Question>
 */
class QuestionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model|TModel>
     */
    protected $model = Question::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'question' => $this->faker->text,
            'status' => QuestionStatus::Unanswered->value,
        ];
    }

    /**
     * Return a factory instance containing a flashcard with a succesful answer attempt.
     *
     * @return QuestionFactory
     */
    public function correct(): QuestionFactory
    {
        return $this->state(fn ($attributes) => ['status' => QuestionStatus::Correct->value]);
    }

    /**
     * Return a factory instance containing a flashcard with a succesful answer attempt.
     *
     * @return QuestionFactory
     */
    public function incorrect(): QuestionFactory
    {
        return $this->state(fn ($attributes) => ['status' => QuestionStatus::Incorrect->value]);
    }
}
