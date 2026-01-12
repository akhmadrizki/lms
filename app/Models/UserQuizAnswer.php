<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserQuizAnswer extends Model
{
    /**
     * The attributes that can be edited in admin (and mass assignable)
     *
     * @var string[]
     */
    protected $fillable = [
        'attempt_id',
        'quiz_question_id',
        'quiz_answer_id',
        'is_correct',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_correct' => 'boolean',
    ];

    /**
     * The attempt that the answer belongs to.
     */
    public function attempt(): BelongsTo
    {
        return $this->belongsTo(UserQuizAttempt::class, 'attempt_id');
    }

    /**
     * The quiz question that the answer belongs to.
     */
    public function quizQuestion(): BelongsTo
    {
        return $this->belongsTo(QuizQuestion::class, 'quiz_question_id');
    }

    /**
     * The quiz answer that the answer belongs to.
     */
    public function quizAnswer(): BelongsTo
    {
        return $this->belongsTo(QuizAnswer::class, 'quiz_answer_id');
    }
}
