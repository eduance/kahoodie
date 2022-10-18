<?php

namespace App\Console\Commands;

use App\Models\Flashcard;
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
    public function handle()
    {
        $cards = Flashcard::with('answer')->get();

        if($cards->count() === 0) {
            $this->comment('Nothing to see here yet!');

            return Command::SUCCESS;
        }

        $this->info('Here is a list of all your flashcards.');
        $this->table(['ID', 'Question', 'Answer'], $cards);

        return Command::SUCCESS;
    }
}
