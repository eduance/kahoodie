<?php

namespace App\Console\Commands;

use App\Kahoodie\Manager;
use Domain\Flashcard\DataTransferObjects\QuestionData;
use Domain\Flashcard\Models\Question;
use Illuminate\Console\Command;

class AllCards extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'flashcard:all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List all the existing flashcards.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(Manager $manager)
    {
        $cards = Question::all();

        if($cards->count() === 0) {
            $this->comment('Nothing to see here yet!');

            return Command::SUCCESS;
        }

        $questionsWithAnswers = QuestionData::collection(Question::all())
            ->include('answer')
            ->except('status', 'id')
            ->toArray();

        $this->info('Here is a list of all your flashcards.');
        $this->table(['Question', 'Answer'], $questionsWithAnswers);

        return Command::SUCCESS;
    }
}
