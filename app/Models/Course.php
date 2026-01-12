<?php

namespace App\Models;

use App\Models\Traits\Uuid;
use App\ShowStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use SoftDeletes, Uuid;
    /**
     * The attributes that can be edited in admin (and mass assignable)
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'description',
        'duration',
        'content',
        'image',
        'coaching_firm_id',
        'show_to',
    ];

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'show_to' => ShowStatus::class,
        ];
    }

    /**
     * The categories that belong to the course.
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(CourseCategory::class, 'course_has_categories', 'course_id', 'category_id');
    }

    /**
     * The lessons that belong to the course.
     */
    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class)->orderBy('rank');
    }

    /**
     * The companies that can see the course.
     */
    public function companies(): BelongsToMany
    {
        return $this->belongsToMany(Company::class, 'company_course', 'course_id', 'company_id');
    }

    /**
     * The coaching firms that can see the course.
     */
    public function coachingFirms(): BelongsToMany
    {
        return $this->belongsToMany(CoachingFirm::class, 'coaching_firm_course', 'course_id', 'coaching_firm_id');
    }

    /**
     * The enrollments for the course.
     */
    public function enrollments(): HasMany
    {
        return $this->hasMany(UserCourseEnrollment::class);
    }
}
