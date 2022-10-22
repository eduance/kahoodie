<?php

namespace App\Console\Commands;

use App\Kahoodie\Game;
use App\Kahoodie\Kahoodie;
use App\Rules\NoCorrectQuestionsAllowed;
use Domain\Flashcard\Actions\AnswerQuestion;
use Domain\Flashcard\DataTransferObjects\QuestionData;
use Domain\Flashcard\Enums\QuestionStatus;
use Domain\Flashcard\Models\Question;
use Domain\Flashcard\ViewModels\GetGameViewModel;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use phpDocumentor\Reflection\Types\Callable_;
use PhpSchool\CliMenu\Exception\InvalidTerminalException;
use Psy\Shell;

class Play extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'flashcard:play';

    /**
     * The question the user is inputting.
     *
     * @var string Question
     */
    protected string $question;

    /**
     * The question model.
     *
     * @var Question $model
     */
    protected Question $model;

    /**
     * The question data object.
     *
     * @var QuestionData $data
     */
    protected $data;

    /**
     * The view model instance we are collecting our data from.
     *
     * @var GetGameViewModel $viewModel
     */
    protected $viewModel;

    /**
     * Set the text to be shown during the execution.
     *
     * @var callable $text
     */
    protected $customMessage;

    /**
     * Get the game manager.
     *
     * @var Kahoodie $manager
     */
    protected Kahoodie $manager;

    /**
     * Get the validator.
     *
     * @var $validator
     */
    protected $validator;

    /**
     * The action to validate any answers.
     *
     * @var AnswerQuestion $action
     */
    protected $action;

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
     *
     * @throws ValidationException
     * @throws InvalidTerminalException
     */
    public function handle(
    ): int
    {
        $game = $this->manager->getGame();

        if ($this->viewModel->questions()->count() === 0) {
            $this->line('Nothing to see here.');

            return Command::SUCCESS;
        }

        if ($this->viewModel->completionRate()->raw === 100.0) {
            $this->info('Congratulations! You finished all the questions.');

            return Command::SUCCESS;
        }

        $game->start();
        $this->warn('You are now in interactive mode, press CTRL+C to exit the terminal.');

        while($game->isRunning()) {
            $this->checkForCompletion();

            try {
                $this->runGame();

                if($this->confirm('Want to have another go?')) {
                    $this->runGame();
                }else{
                    $game->stop();
                }
            } catch (Exception $exception) {
                $this->setMessage(fn () => $this->error('Something went wrong: ' . $exception->getMessage()));
                $this->runGame();
            }
        }

        $this->line('Thanks for playing!');

        $this->manager->reopen();

        return Command::SUCCESS;
    }

    /**
     * Run the game.
     *
     * @return int
     *
     * @throws ValidationException
     * @throws Exception
     */
    protected function runGame(): int
    {
        $this->table(['ID', 'Question', 'Status'], $this->viewModel->questions()->toArray());
        $this->line(sprintf('You have completed %s of all the questions', $this->viewModel->completionRate()));

        $this->displayMessages();

        $this->question = $this->ask('What question would you like to practice?');

        $this->validate();

        $validated = collect($this->validator->validated());
        $question = Question::findOrFail($validated->get('question'));

        $this->data = QuestionData::from($question);
        $answer = $this->ask($this->data->question);

        $answerChecked = $this->action->handle(question: $this->data, answer: $answer);
        $answerChecked
            ? $this->setMessage(fn () => $this->line('Nice job, correct!'))
            : $this->setMessage(fn () => $this->error('Oops, try again.') );

        if(config('app.env') === 'testing') {
            return Command::SUCCESS;
        }

        return $this->runGame();
    }

    /**
     * Check whether the player has reached 100% score.
     *
     * @return void
     */
    protected function checkForCompletion(): void
    {
        if ($this->viewModel->completionRate()->raw === 100.0) {
            $this->info('Congratulations! You finished all the questions.');
        }
    }

    /**
     * The logic for displaying any messages.
     *
     * @return void
     */
    protected function displayMessages()
    {
        if($this->customMessage) {
            $this->newLine();
            tap($this->customMessage, fn ($customMessage) => $customMessage());
        }
    }

    /**
     * Validate the question.
     *
     * @return void
     *
     * @throws ValidationException
     */
    protected function validate()
    {
        $this->validator = Validator::make(['question' => $this->question],
            [
                'question' => ['bail', 'required', 'exists:questions,id', 'integer', new NoCorrectQuestionsAllowed]
            ]
        );

        if ($this->validator->fails()) {
            foreach ($this->validator->errors()->all() as $error) {
                $this->setMessage(fn () => $this->error($error));
            }

            $this->runGame();
        }
    }

    /**
     * Set the game error message.
     *
     * @param callable $text
     * @return void
     */
    protected function setMessage(callable $text)
    {
        $this->customMessage = $text;
    }
}
