<?php

namespace App\Models;

use App\Helpers\AdminHelpers;
use App\Traits\StatusTrait;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Story extends Model implements TranslatableContract
{
    use HasFactory, Translatable, StatusTrait, SoftDeletes;

    public $translatedAttributes = ['title', 'excerpt', 'body'];
    protected $fillable = ['slug', 'type', 'image', 'gallery', 'video_link', 'featured', 'status', 'program_id', 'project_id'];

    protected $casts = [
        'gallery' => 'array',
    ];

    public function program(): BelongsTo {
        return $this->belongsTo(Program::class);
    }

    public function project(): BelongsTo {
        return $this->belongsTo(Project::class);
    }

    public function scopeActive(Builder $q) {
        $q->where('status', 1);
    }

    protected static function booted(): void {
        static::deleted(function (Story $story) {
            AdminHelpers::removeModelImage($story->image);
        });
    }

    // for search
    protected $appends  = ["showUrl"];

    public function getShowUrlAttribute($value)
    {
        return route('stories.show', $this);
    }
}
