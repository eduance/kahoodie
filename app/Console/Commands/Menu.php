<?php

namespace App\Console\Commands;

use App\Kahoodie\Kahoodie;
use Closure;
use Illuminate\Console\Command;
use PhpSchool\CliMenu\Builder\CliMenuBuilder;
use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\Exception\InvalidTerminalException;

class Menu extends Command
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
        made with ♥ by Brandon
        ART;

        $this->manager = app(Kahoodie::class);

        $menu = (new CliMenuBuilder)
            ->addAsciiArt($logo)
            ->addLineBreak('=')
            ->addItem('Start a new game', $this->getCallback(Play::class))
            ->addItem('All cards', $this->getCallback(AllCards::class))
            ->addItem('Create a card', $this->getCallback(CreateFlashcard::class))
            ->addLineBreak('-')
            ->addItem('STATS', $this->getCallback(Stats::class))
            ->addItem('RESET', $this->getCallback(Reset::class))
            ->setExitButtonText('EXIT')
            ->setBackgroundColour('magenta')
            ->setForegroundColour('white')
            ->setWidth(70)
            ->setMarginAuto()
            ->build();

        $menu->open();
    }

    /**
     * Get an instance of the command.
     *
     * @param $command
     * @return Closure|false|null
     */
    protected function getCallback($command)
    {
        $closure = function (CliMenu $menu) use ($command) {
            $this->manager->boot($menu);
            $menu->close();
            $this->call($command);
        };

        return $closure->bindTo($this);
    }
}
