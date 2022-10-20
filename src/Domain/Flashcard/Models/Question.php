<?php

namespace Domain\Flashcard\Models;

use Domain\Flashcard\Builders\QuestionBuilder;
use Domain\Flashcard\Enums\QuestionStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Question extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'question',
        'status'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'status' => QuestionStatus::class,
    ];

    public function newEloquentBuilder($query)
    {
        return new QuestionBuilder($query);
    }

    /**
     * A question has one answer.
     *
     * @return HasOne
     */
    public function answer()
    {
        return $this->hasOne(Answer::class);
    }

    /**
     * A flashcard has many attempts.
     *
     * @return HasMany
     */
    public function attempts()
    {
        return $this->hasMany(Attempt::class);
    }
}
