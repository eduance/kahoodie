<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

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
        return Command::SUCCESS;
    }
}
