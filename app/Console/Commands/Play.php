<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Play extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'flashcard:play';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Practice using Kahoodie!';

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
