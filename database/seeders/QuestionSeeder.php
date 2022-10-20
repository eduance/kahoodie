<?php

namespace Database\Seeders;

use Domain\Flashcard\Models\Answer;
use Domain\Flashcard\Models\Question;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @see https://www.studocu.com/blog/en/fun-facts-about-studocu-did-you-know
     */
    public function run()
    {
        Question::factory()->has(Answer::factory()->state(['text' => 2013]))->create(['question' => 'When was Studocu born?']);
        Question::factory()->has(Answer::factory()->state(['text' => 'Keizersgracht']))->create(['question' => 'Where is Studocu situated?']);
        Question::factory()->has(Answer::factory()->state(['text' => 'Dusty']))->create(['question' => 'What is the name of the Studocu dog?']);
        Question::factory()->has(Answer::factory()->state(['text' => 'Michael Scott']))->create(['question' => 'Who is the best office character?']);
        Question::factory()->has(Answer::factory()->state(['text' => 'Conan O Brien']))->create(['question' => 'Funniest talkshow host ever']);
    }
}
