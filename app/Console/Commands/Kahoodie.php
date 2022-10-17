<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpSchool\CliMenu\Builder\CliMenuBuilder;
use PhpSchool\CliMenu\CliMenu;

class Kahoodie extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'flashcard:interactive';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start an interactive session with Kahoodie.';

    /**
     * Execute the console command.
     *
     * @return void
     *
     * @throws \PhpSchool\CliMenu\Exception\InvalidTerminalException
     */
    public function handle()
    {
        // We will start of by building our dream dialog.
        // Then later on, we will move the right logic into its right place and refactor as we go.
        // First, let's have some building fun. <3

        $logo = <<<ART
        ██╗  ██╗ █████╗ ██╗  ██╗ ██████╗  ██████╗ ██████╗ ██╗███████╗
        ██║ ██╔╝██╔══██╗██║  ██║██╔═══██╗██╔═══██╗██╔══██╗██║██╔════╝
        █████╔╝ ███████║███████║██║   ██║██║   ██║██║  ██║██║█████╗
        ██╔═██╗ ██╔══██║██╔══██║██║   ██║██║   ██║██║  ██║██║██╔══╝
        ██║  ██╗██║  ██║██║  ██║╚██████╔╝╚██████╔╝██████╔╝██║███████╗
        ╚═╝  ╚═╝╚═╝  ╚═╝╚═╝  ╚═╝ ╚═════╝  ╚═════╝ ╚═════╝ ╚═╝╚══════╝
        made with ♥ by Brandon for Studocu
        ART;

        $itemCallable = function (CliMenu $menu) {
            echo $menu->getSelectedItem()->getText();
        };

        $menu = (new CliMenuBuilder)
            ->addAsciiArt($logo)
            ->addLineBreak('=')
            ->addItem('Start a new game', $itemCallable)
            ->addItem('All cards', $itemCallable)
            ->addItem('Create a card', $itemCallable)
            ->addLineBreak('-')
            ->addItem('STATS', $itemCallable)
            ->addItem('RESET', $itemCallable)
            ->setExitButtonText('EXIT')
            ->setBackgroundColour('magenta')
            ->setForegroundColour('white')
            ->setWidth(70)
            ->setMarginAuto()
            ->build();

        $menu->open();
    }
}
