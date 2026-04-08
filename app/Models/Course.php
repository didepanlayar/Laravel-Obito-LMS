<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Course extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'thumbnail',
        'about',
        'category_id',
        'is_popular'
    ];

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    // Course has many benefits
    public function benefits(): HasMany
    {
        return $this->hasMany(CourseBenefit::class);
    }

    // Course has many course sections
    public function courseSections(): HasMany
    {
        return $this->hasMany(CourseBenefit::class);
    }

    // Course has many course students
    public function courseStudents(): HasMany
    {
        return $this->hasMany(CourseStudent::class, 'course_id');
    }

    // Course has many course mentors
    public function courseMentors(): HasMany
    {
        return $this->hasMany(CourseMentor::class, 'course_id');
    }

    // Course belongs to category
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
