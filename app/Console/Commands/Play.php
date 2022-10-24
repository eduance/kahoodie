<?php

namespace App\Console\Commands;

use App\Kahoodie\Game;
use App\Kahoodie\Kahoodie;
use Domain\Flashcard\Actions\AnswerQuestion;
use Domain\Flashcard\DataTransferObjects\QuestionData;
use Domain\Flashcard\Models\Question;
use Domain\Flashcard\ViewModels\GetGameViewModel;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Validation\ValidationException;

class Play extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'flashcard:play';

    /**
     * @var
     */
    public $answer;

    /**
     * The question the user is inputting.
     *
     * @var string|null Question
     */
    public string|null $question;

    /**
     * The question model.
     *
     * @var Question $model
     */
    public Question $model;

    /**
     * The question data object.
     *
     * @var QuestionData $data
     */
    protected QuestionData $data;

    /**
     * The view model instance we are collecting our data from.
     *
     * @var GetGameViewModel $viewModel
     */
    protected $viewModel;

    /**
     * Get the game manager.
     *
     * @var Kahoodie $manager
     */
    protected Kahoodie $manager;

    /**
     * The action to validate any answers.
     *
     * @var AnswerQuestion $action
     */
    protected $action;

    /**
     * The game object.
     *
     * @var Game $game
     */
    protected $game;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Practice using Kahoodie!';

    public function __construct()
    {
        parent::__construct();

        $this->viewModel = app(GetGameViewModel::class);
        $this->manager = app(Kahoodie::class);
        $this->action = app(AnswerQuestion::class);
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        try {
            tap($this->game = $this->manager->getGame(), function ($game) {
                $game->setView($this);
                $this->warn('You are now in interactive mode, press CTRL+C to exit the terminal.');
                $game->start();
            });
        } catch (Exception $exception) {
            $this->game->setMessage(fn () => $this->error('Something went wrong: ' . $exception->getMessage()));
        }

        $this->line('Thanks for playing!');

        return Command::SUCCESS;
    }

    /**
     * Additional validation.
     *
     * @return int|void
     */
    public function validateBefore()
    {
        if ($this->viewModel->questions()->count() === 0) {
            $this->line('Nothing to see here.');

            return Command::SUCCESS;
        }

        if ($this->viewModel->completionRate()->raw === 100.0) {
            $this->line('Congratulations! You finished all the questions.');

            return $this->stop();
        }
    }

    /**
     * Run the game.
     *
     * @throws ValidationException
     * @throws Exception
     */
    public function takeAnswer(): int
    {
        $validated = $this->game->validated;
        $question = Question::findOrFail($validated->get('question'));

        $this->data = QuestionData::from($question);

        $givenAnswer = $this->ask($this->data->question);
        $submittedAnswer = $this->action->handle(question: $this->data, answer: $givenAnswer);

        if($submittedAnswer) {
            $this->game->setMessage(fn () => $this->line('Nice job, correct!'));
        }else{
            $this->game->setMessage(fn () => $this->error('Oops, try again.') );
        }

        return Command::SUCCESS;
    }

    public function header()
    {
        $this->table(['ID', 'Question', 'Status'], $this->viewModel->questions()->toArray());
        $this->line(sprintf('You have completed %s of all the questions', $this->viewModel->completionRate()));

        $this->displayMessages();

        $this->question = $this->ask('What question would you like to practice?');
    }

    /**
     * The logic for displaying any messages.
     *
     * @return void
     */
    protected function displayMessages()
    {
        if($this->game->getMessage()) {
            $this->newLine();
            tap($this->game->getMessage(), fn ($message) => $message());
        }
    }

    /**
     * Stop the execution of the command.
     *
     * @return int
     */
    public function stop(): int
    {
        return Command::SUCCESS;
    }
}
