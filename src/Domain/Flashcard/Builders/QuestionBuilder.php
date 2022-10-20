<?php

namespace Domain\Flashcard\Builders;

use Domain\Flashcard\Enums\QuestionStatus;
use Illuminate\Database\Eloquent\Builder;

class QuestionBuilder extends Builder
{
    /**
     * Get the questions that have been correctly answered.
     *
     * @return self
     */
    public function whereCorrect(): self
    {
        return $this->whereStatus(QuestionStatus::Correct);
    }

    /**
     * Get the total answer count.
     *
     * @return int
     */
    public function answerCount()
    {
        return $this->has('attempts')->count();
    }
}
