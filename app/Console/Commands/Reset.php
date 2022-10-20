<?php

namespace App\Console\Commands;

use App\Events\ProcessQuestion;
use App\Kahoodie\Kahoodie;
use Domain\Flashcard\Enums\QuestionStatus;
use Domain\Flashcard\Models\Question;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class Reset extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'flashcard:reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset the game for the current player and start over.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(Kahoodie $manager)
    {
        $questions = Question::has('attempts')->get();

        DB::beginTransaction();

        $questions->map(function ($question) {
            $question->attempts()->delete();
            ProcessQuestion::dispatch($question, QuestionStatus::Unanswered);
        });

        DB::commit();

        $this->info('We resetted your progress. Goodluck!');

        $continue = $this->ask('Type anything to continue');
        if($continue) {
            $manager->reopen();
        }
    }
}
