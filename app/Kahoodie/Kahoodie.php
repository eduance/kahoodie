<?php

namespace App\Kahoodie;

use Illuminate\Console\Command;
use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\Exception\InvalidTerminalException;

class Kahoodie
{
    /**
     * The Kahoodie interactive menu.
     *
     * @var CliMenu $menu
     */
    protected CliMenu $menu;

    /**
     * Booted upon calling of the main command.
     *
     * @var bool
     */
    protected bool $booted = false;

    /**
     * Booted upon calling of the main command.
     *
     * @var Game $game
     */
    protected Game $game;

    /**
     * The command to render output to.
     *
     * @var Command $view
     */
    protected Command $view;

    /**
     * Get the game current state.
     *
     * @var bool
     */
    protected bool $started = false;

    /**
     * Set the CLI Menu.
     *
     * @return void
     */
    protected function setMenu(CliMenu $menu)
    {
        $this->menu = $menu;
    }

    /**
     * See if Kahoodie has been booted.
     *
     * @return bool
     */
    public function booted(): bool
    {
        return $this->booted;
    }

    /**
     * Open Kahoodie if accessed via menu.
     *
     * @return void
     * @throws InvalidTerminalException
     */
    public function reopen(): void
    {
        if($this->booted()) {
            $this->menu->open();
        }
    }

    /**
     * Boot Kahoodie.
     *
     * @param $menu
     * @param $class
     * @return void
     */
    public function boot($menu): void
    {
        $this->setMenu($menu);
        $this->setGame(new Game());

        $this->booted = true;
    }

    /**
     * Get the view.
     *
     * @return mixed
     */
    public function getView()
    {
        return $this->view;
    }

    /**
     * Get the CLI Menu.
     *
     * @return CliMenu
     */
    public function getMenu(): CliMenu
    {
        return $this->menu;
    }

    /**
     * Set the instance of a game.
     *
     * @param Game $game
     * @return void
     */
    public function setGame(Game $game): void
    {
        $this->game = $game;
    }

    /**
     * Get the game object.
     *
     * @return Game
     */
    public function getGame(): Game
    {
        return $this->game;
    }

    public function setView($view)
    {
        $this->view = $view;
    }
}
