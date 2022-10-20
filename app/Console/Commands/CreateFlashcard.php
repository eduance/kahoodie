<?php

namespace App\Console\Commands;

use App\Kahoodie\Kahoodie;
use Domain\Flashcard\Models\Answer;
use Domain\Flashcard\Models\Question;
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
    protected $description = 'Create a new Flashcard.';

    /**
     * Execute the console command.
     *
     * @return int
     *
     * @throws \PhpSchool\CliMenu\Exception\InvalidTerminalException
     */
    public function handle(Kahoodie $manager)
    {
        $question = $this->ask('What question would you like the players to ask?');
        $answer = $this->ask('What is the corresponding answer?');

        $validator = Validator::make([
            'question' => $question,
            'answer' => $answer,
        ], [
           'question' => ['required', 'unique:questions,question'],
           'answer' => ['required', 'unique:answers,text'],
        ]);

        if($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return Command::FAILURE;
        }

        $question = Question::create([
            'question' => $question,
        ]);

        $answer = Answer::create([
            'text' => $answer
        ]);

        $question->answer()->save($answer);
        $this->line('Congratulations! Your question has succesfully been added to Kahoodie.');

        $continue = $this->ask('Type anything to continue');
        if($continue) {
            $manager->reopen();
        }

        return Command::SUCCESS;
    }
}
