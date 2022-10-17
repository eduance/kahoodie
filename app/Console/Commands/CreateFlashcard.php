<?php

namespace App\Console\Commands;

use App\Models\Answer;
use App\Models\Question;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

class CreateFlashcard extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'flashcard:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a new Flashcard.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $question = $this->ask('What question would you like the players to ask?');
        $answer = $this->ask('What is the corresponding answer?');

        $validator = Validator::make([
            'question' => $question,
            'answer' => $answer,
        ], [
           'question' => ['required'],
           'answer' => ['required'],
        ]);

        $validator->after(function ($validator) use ($question, $answer) {
            if(Question::whereText($question)->first()) {
                $validator->errors()->add('question', 'We already have a similar question.');
            }

            if(Answer::whereText($answer)->first()) {
                $validator->errors()->add('answer', 'We already have a similar answer.');
            }
        });

        if($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return Command::FAILURE;
        }

        $question = Question::create([
            'text' => $question,
        ]);

        $answer = Answer::create([
            'text' => $answer
        ]);

        $question->answer()->save($answer);

        $this->line('Congratulations! Your question has succesfully been added to Kahoodie.');
        return Command::SUCCESS;
    }
}
