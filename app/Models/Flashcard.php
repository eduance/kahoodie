<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Flashcard extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'question'
    ];

    /**
     * A question has one answer.
     *
     * @return HasOne
     */
    public function answer()
    {
        return $this->hasOne(Answer::class);
    }
}
