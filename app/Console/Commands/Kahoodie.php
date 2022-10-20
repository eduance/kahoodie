<?php

namespace App\Console\Commands;

use App\Kahoodie\Manager;
use Illuminate\Console\Command;
use PhpSchool\CliMenu\Builder\CliMenuBuilder;
use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\Exception\InvalidTerminalException;

class Kahoodie extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'flashcard:interactive';

    protected $manager;

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
     * @throws InvalidTerminalException
     */
    public function handle()
    {
        $logo = <<<ART
        ██╗  ██╗ █████╗ ██╗  ██╗ ██████╗  ██████╗ ██████╗ ██╗███████╗
        ██║ ██╔╝██╔══██╗██║  ██║██╔═══██╗██╔═══██╗██╔══██╗██║██╔════╝
        █████╔╝ ███████║███████║██║   ██║██║   ██║██║  ██║██║█████╗
        ██╔═██╗ ██╔══██║██╔══██║██║   ██║██║   ██║██║  ██║██║██╔══╝
        ██║  ██╗██║  ██║██║  ██║╚██████╔╝╚██████╔╝██████╔╝██║███████╗
        ╚═╝  ╚═╝╚═╝  ╚═╝╚═╝  ╚═╝ ╚═════╝  ╚═════╝ ╚═════╝ ╚═╝╚══════╝
        made with ♥ by Brandon for Studocu
        ART;

        $this->manager = app(Manager::class);

        $menu = (new CliMenuBuilder)
            ->addAsciiArt($logo)
            ->addLineBreak('=')
            ->addItem('Start a new game', $this->getCallback(Play::class))
            ->addItem('All cards', $this->getCallback(AllCards::class))
            ->addItem('Create a card', $this->getCallback(CreateFlashcard::class))
            ->addLineBreak('-')
            ->addItem('STATS', $this->getCallback(Stats::class))
            ->addItem('RESET', $this->getCallback(Play::class))
            ->setExitButtonText('EXIT')
            ->setBackgroundColour('magenta')
            ->setForegroundColour('white')
            ->setWidth(70)
            ->setMarginAuto()
            ->build();

        $menu->open();
    }

    /**
     * Call the command.
     *
     * @param $command
     * @return \Closure|false|null
     */
    protected function getCallback($command)
    {
        $closure = function (CliMenu $menu) use ($command) {
            $this->manager->setMenu($menu);
            $this->manager->boot();
            $menu->close();
            $this->call($command);
        };

        return $closure->bindTo($this);
    }
}
