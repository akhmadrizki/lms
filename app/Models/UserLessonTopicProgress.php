<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserLessonTopicProgress extends Model
{
    /**
     * The attributes that can be edited in admin (and mass assignable)
     *
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'lesson_topic_id',
        'enrollment_id',
        'is_completed',
        'completed_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'completed_at' => 'datetime',
    ];

    /**
     * The user that owns the progress.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The lesson topic that the progress belongs to.
     */
    public function lessonTopic(): BelongsTo
    {
        return $this->belongsTo(LessonTopic::class, 'lesson_topic_id');
    }
    
    /**
     * The enrollment that the progress belongs to.
     */
    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(UserCourseEnrollment::class, 'enrollment_id');
    }
}
