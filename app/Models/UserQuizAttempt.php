<?php

namespace App\Models;

use App\Models\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserQuizAttempt extends Model
{
    use Uuid;

    /**
     * The attributes that can be edited in admin (and mass assignable)
     *
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'quiz_id',
        'enrollment_id',
        'score',
        'total_questions',
        'is_passed',
        'started_at',
        'completed_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    /**
     * The user that owns the attempt.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The quiz that the attempt belongs to.
     */
    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    /**
     * The enrollment that the attempt belongs to.
     */
    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(UserCourseEnrollment::class, 'enrollment_id');
    }

    /**
     * The answers for the attempt.
     */
    public function answers(): HasMany
    {
        return $this->hasMany(UserQuizAnswer::class, 'attempt_id');
    }
}
