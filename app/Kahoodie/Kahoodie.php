<?php

namespace App\Kahoodie;

use PhpSchool\CliMenu\CliMenu;

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
    public function setMenu(CliMenu $menu)
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
     * Check whether the game is running.
     *
     * @return bool
     */
    public function isGameRunning(): bool
    {
        return $this->started;
    }

    /**
     * Start the game.
     *
     * @return bool
     */
    public function startGame(): bool
    {
        return $this->started = true;
    }

    /**
     * Start the game.
     *
     * @return bool
     */
    public function stopGame(): bool
    {
        return $this->started = false;
    }

    /**
     * Open Kahoodie if accessed via menu.
     *
     * @return void
     * @throws \PhpSchool\CliMenu\Exception\InvalidTerminalException
     */
    public function reopen()
    {
        if($this->booted()) {
            $this->menu->open();
        }
    }

    /**
     * Boot Kahoodie.
     *
     * @return bool
     */
    public function boot($menu)
    {
        $this->booted = true;
        $this->setMenu($menu);
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
}
