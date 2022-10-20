<?php

namespace App\Console\Commands;

use App\Kahoodie\Kahoodie;
use Domain\Flashcard\ViewModels\GetStatsViewModel;
use Illuminate\Console\Command;

class Stats extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'flashcard:stats';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get the statistics for the player.';

    /**
     * Execute the console command.
     *
     * @return int
     * @throws \PhpSchool\CliMenu\Exception\InvalidTerminalException
     */
    public function handle(GetStatsViewModel $viewModel, Kahoodie $manager)
    {
        $this->newLine();
        $this->info('Hoohaa. Look at those numbers, keep shining!');

        $this->table([
            'Total questions',
            '% of questions with an answer',
            '% of questions with a correct answer'
        ], [
           [
               $viewModel->totalQuestionCount(),
               $viewModel->relativeQuestionsWithAnswer(),
               $viewModel->relativeQuestionsWithCorrectAnswer()]
        ]);

        $continue = $this->ask('Type anything to continue');
        if($continue) {
            $manager->reopen();
        }
    }
}
