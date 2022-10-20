<?php

namespace App\Console\Commands;

use App\Kahoodie\Kahoodie;
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
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Practice using Kahoodie!';

    public function __construct()
    {
        parent::__construct();

        $this->viewModel = app(GetGameViewModel::class);
    }

    /**
     * Execute the console command.
     *
     * @param AnswerQuestion $action
     * @param Kahoodie $manager
     * @return int
     *
     * @throws InvalidTerminalException
     * @throws ValidationException
     */
    public function handle(
        AnswerQuestion $action,
        Kahoodie $manager
    ): int
    {
        if ($this->viewModel->questions()->count() === 0) {
            $this->line('Nothing to see here.');

            return Command::SUCCESS;
        }

        $this->checkForCompletion();

        $manager->startGame();
        $this->warn('You are now in interactive mode, press CTRL+C to exit the terminal.');

        while($manager->isGameRunning()) {
            $this->checkForCompletion();

            try {
                $answer = $this->runGame();

                $answerChecked = $action->handle(question: $this->data, answer: $answer);
                $answerChecked ? $this->line('Nice job, correct!') : $this->error('Oops, try again.');

                if($this->confirm('Want to have another go?')) {
                    $this->runGame();
                }else{
                    $manager->stopGame();
                }
            } catch (Exception $exception) {
                $this->setMessage(fn () => $this->error('Something went wrong: ' . $exception->getMessage()));
                $this->runGame();
            }
        }

        $this->line('Thanks for playing!');

        if($manager->booted()) {
            $manager->reopen();
        }

        return Command::SUCCESS;
    }

    /**
     * Run the game.
     *
     * @return mixed
     * @throws ValidationException
     */
    protected function runGame(): mixed
    {
        $this->table(['ID', 'Question', 'Status'], $this->viewModel->questions()->toArray());
        $this->line(sprintf('You have completed %s of all the questions', $this->viewModel->completionRate()));

        if($this->customMessage) {
            $this->newLine();
            tap($this->customMessage, fn ($customMessage) => $customMessage());
        }

        $this->question = $this->ask('What question would you like to practice?');

        $validator = $this->validate();
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->setMessage(fn () => $this->error($error));
            }

            $this->runGame();
        }

        $this->data = QuestionData::from(Question::findOrFail($validator->validated()['question']));

        if ($this->data->status === QuestionStatus::Correct) {
            $this->setMessage(fn () => $this->error('You cannot practice the same question multiple times.'));
            $this->runGame();
        }

        return $this->ask($this->data->question);
    }

    /**
     * Check whether the player has reached 100% score.
     *
     * @return int|void
     */
    protected function checkForCompletion()
    {
        if ($this->viewModel->completionRate()->raw === 100.0) {
            $this->info('Congratulations! You finished all the questions.');

            return Command::SUCCESS;
        }
    }

    /**
     * Validate the question.
     *
     * @return \Illuminate\Contracts\Validation\Validator|\Illuminate\Validation\Validator
     *
     * @throws ValidationException
     */
    protected function validate()
    {
        return Validator::make(['question' => $this->question],
            [
                'question' =>
                    ['required', 'exists:questions,id', 'integer']
            ]
        );
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
