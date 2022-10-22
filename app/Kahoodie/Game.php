<?php

namespace App\Kahoodie;

class Game
{
    /**
     * The current state of the game.
     *
     * @return bool $started
     */
    protected bool $started;

    /**
     * Check whether the game is running.
     *
     * @return bool
     */
    public function isRunning(): bool
    {
        return $this->started;
    }

    /**
     * Start the game.
     *
     * @return bool
     */
    public function start(): bool
    {
        return $this->started = true;
    }

    /**
     * Stop the game.
     *
     * @return bool
     */
    public function stop(): bool
    {
        return $this->started = false;
    }

    public function stopWithError()
    {
        'Congratulations! You finished all the questions.';
    }
}
