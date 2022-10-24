<?php

namespace App\Kahoodie;

use App\Rules\NoCorrectQuestionsAllowed;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class Game
{
    /**
     * The current state of the game.
     *
     * @return bool $started
     */
    protected bool $started;

    /**
     * The message to be displayed.
     *
     * @var $message
     */
    protected $message;

    /**
     * The validator instance.
     *
     * @var Validator $validator
     */
    public $validator;

    /**
     * The output to render to.
     *
     * @var Command $view
     */
    public $view;

    /**
     * The validated collection.
     *
     * @var Collection $validated
     */
    public $validated;

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
     * @return void
     * @throws ValidationException
     */
    public function start(): void
    {
        $this->started = true;

        $this->run();
    }

    /**
     * Run the game.
     *
     * @return void
     *
     * @throws ValidationException
     */
    public function run()
    {
        while($this->isRunning()) {
            if(method_exists($this->view, 'validateBefore')) {
                $this->view->validateBefore();
            }

            if(method_exists($this->view, 'header')) {
                $this->view->header();
            }

            $this->validate();

            if(method_exists($this->view, 'takeAnswer')) {
                $this->view->takeAnswer();
            }

            if(config('app.env') === 'testing') {
                return $this->view->stop();
            }
        }
    }

    /**
     * Validate the fields.
     *
     * @throws ValidationException
     */
    public function validate()
    {
        $this->validator = Validator::make(['question' => $this->view->question],
            [
                'question' => ['bail', 'required', 'exists:questions,id', 'integer', new NoCorrectQuestionsAllowed]
            ]
        );

        if ($this->validator->fails()) {
            foreach ($this->validator->errors()->all() as $error) {
                $this->setMessage(fn () => $this->view->error($error));
            }

            $this->run();
        }

        $this->validated = collect($this->validator->validated());
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

    /**
     * Set the message for the given run.
     *
     * @param $message
     * @return void
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    public function setView($view)
    {
        $this->view = $view;
    }

    /**
     * Set the message for the given run.
     *
     * @return callable|null $message
     */
    public function getMessage(): callable|null
    {
        return $this->message;
    }
}
