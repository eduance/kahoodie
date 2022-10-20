<?php

namespace Database\Factories;

use Domain\Flashcard\Models\Attempt;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Attempt>
 */
class AttemptFactory extends Factory
{
    protected $model = Attempt::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'correct' => true,
        ];
    }

    /**
     * Indicate that the attempt was correctly answered.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function correct()
    {
        return $this->state(function (array $attributes) {
            return [
                'correct' => true,
            ];
        });
    }

    /**
     * Indicate that the attempt was incorrect.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function incorrect()
    {
        return $this->state(function (array $attributes) {
            return [
                'correct' => false,
            ];
        });
    }
}
