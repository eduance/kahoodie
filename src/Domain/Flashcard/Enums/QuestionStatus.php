<?php

namespace Domain\Flashcard\Enums;

enum QuestionStatus: string
{
    case Unanswered = 'unanswered';
    case Correct = 'correct';
    case Incorrect = 'incorrect';
}
