<?php

namespace App\Models;

use App\CourseEnrollmentStatus;
use App\Models\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class UserCourseEnrollment extends Model
{
    use Uuid;

    /**
     * The attributes that can be edited in admin (and mass assignable)
     *
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'course_id',
        'enrollable_id',
        'enrollable_type',
        'started_at',
        'completed_at',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'status' => CourseEnrollmentStatus::class,
    ];

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    /**
     * The user that owns the enrollment.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The course that the enrollment belongs to.
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * The enrollable that the enrollment belongs to.
     */
    public function enrollable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * The topic progress for the enrollment.
     */
    public function topicProgress(): HasMany
    {
        return $this->hasMany(UserLessonTopicProgress::class, 'enrollment_id');
    }

    /**
     * The quiz attempts for the enrollment.
     */
    public function quizAttempts(): HasMany
    {
        return $this->hasMany(UserQuizAttempt::class, 'enrollment_id');
    }

    /**
     * Calculate the progress percentage for the enrollment.
     */
    public function calculateProgress(): int
    {
        $course = $this->course()->with('lessons.topics', 'lessons.quizzes')->first();

        $totalItems = 0;
        $completedItems = 0;

        // Count topics
        foreach ($course->lessons as $lesson) {
            $totalItems += $lesson->topics->count();
            $completedItems += $this->topicProgress()
                ->whereIn('lesson_topic_id', $lesson->topics->pluck('id'))
                ->where('is_completed', true)
                ->count();

            // Count quizzes
            if ($lesson->quizzes->count() > 0) {
                $totalItems += $lesson->quizzes->count();
                $completedItems += $this->quizAttempts()
                    ->whereIn('quiz_id', $lesson->quizzes->pluck('id'))
                    ->where('is_passed', true)
                    ->distinct('quiz_id')
                    ->count('quiz_id');
            }
        }

        return $totalItems > 0 ? round(($completedItems / $totalItems) * 100) : 0;
    }
}
