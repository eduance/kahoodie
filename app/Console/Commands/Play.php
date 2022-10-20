<?php

namespace App\Console\Commands;

use App\Kahoodie\Manager;
use Domain\Flashcard\Actions\AnswerQuestion;
use Domain\Flashcard\DataTransferObjects\QuestionData;
use Domain\Flashcard\Enums\QuestionStatus;
use Domain\Flashcard\Models\Question;
use Domain\Flashcard\ViewModels\GetGameViewModel;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use PhpSchool\CliMenu\Exception\InvalidTerminalException;

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
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Practice using Kahoodie!';

    /**
     * Execute the console command.
     *
     * @param AnswerQuestion $action
     * @param GetGameViewModel $viewModel
     * @param Manager $manager
     * @return int
     *
     * @throws InvalidTerminalException
     */
    public function handle(
        AnswerQuestion $action,
        GetGameViewModel $viewModel,
        Manager $manager
    ): int
    {
        if ($viewModel->questions()->count() === 0) {
            $this->line('Nothing to see here.');

            return Command::SUCCESS;
        }

        if ($viewModel->completionRate()->raw === 100.0) {
            $this->info('Congratulations! You finished all the questions.');

            return Command::SUCCESS;
        }

        $this->warn('You are now in interactive mode, press CTRL+C to exit the terminal.');

        $answer = $this->startGame();
        $answerChecked = $action->handle(question: $this->data, answer: $answer);

        if (! $answerChecked) {
            $this->error('Oops, try again.');
        }

        if ($answerChecked) {
            $this->line('Nice job, correct!');
        }

        if($this->confirm('Want to have another go?')) {
            $this->startGame();
        }

        $this->line('Thanks for playing!');

        $manager->reopen();

        return Command::SUCCESS;
    }

    public function startGame()
    {
        $viewModel = app(GetGameViewModel::class);

        $this->table(['ID', 'Question', 'Status'], $viewModel->questions()->toArray());
        $this->line(sprintf('You have completed %s of all the questions', $viewModel->completionRate()));

        $this->question = $this->ask('What question would you like to practice?');

        $validator = $this->validate();
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            $this->newLine(2);
            $this->startGame($viewModel);
        }

        $this->data = QuestionData::from(Question::findOrFail($validator->validated()['question']));

        if ($this->data->status === QuestionStatus::Correct) {
            $this->error('You cannot practice the same question multiple times.');

            return 1;
        }

        return $this->ask($this->data->question);
    }

    /**
     * Validate the question.
     *
     * @return \Illuminate\Contracts\Validation\Validator|\Illuminate\Validation\Validator
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
}
