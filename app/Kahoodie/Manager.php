<?php

namespace App\Kahoodie;

use PhpSchool\CliMenu\CliMenu;

class Manager
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
     * @return void
     */
    public function boot()
    {
        $this->booted = true;
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
