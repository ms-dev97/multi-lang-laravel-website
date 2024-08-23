<?php

namespace App\Models;

use App\Helpers\AdminHelpers;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Project extends Model implements TranslatableContract
{
    use HasFactory, Translatable;

    public $translatedAttributes = ['title', 'excerpt', 'body'];
    protected $fillable = ['slug', 'image', 'cover', 'gallery', 'featured', 'status', 'program_id'];

    protected $casts = [
        'gallery' => 'array',
    ];

    public function news(): BelongsToMany {
        return $this->belongsToMany(News::class, 'news_project', 'project_id', 'news_id');
    }

    public function program(): BelongsTo {
        return $this->belongsTo(Program::class);
    }

    public function scopeActive(Builder $q) {
        $q->where('status', 1);
    }

    protected static function booted(): void {
        static::deleted(function (Project $project) {
            AdminHelpers::removeModelImage($project->image);
            AdminHelpers::removeModelImage($project->cover);

            $project->news()->detach();
        });
    }
}
