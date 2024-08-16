<?php

namespace App\Models;

use App\Helpers\AdminHelpers;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Program extends Model implements TranslatableContract
{
    use HasFactory, Translatable;

    public $translatedAttributes = ['title', 'excerpt', 'body'];
    protected $fillable = ['slug', 'image', 'icon', 'cover', 'gallery', 'featured', 'status'];

    protected $casts = [
        'gallery' => 'array',
    ];

    public function news(): BelongsToMany {
        return $this->belongsToMany(News::class, 'news_program', 'program_id', 'news_id');
    }

    public function projects(): HasMany {
        return $this->hasMany(Project::class);
    }

    public function stories(): HasMany {
        return $this->hasMany(Story::class);
    }

    public function scopeActive(Builder $q) {
        $q->where('status', 1);
    }

    protected static function booted(): void {
        static::deleted(function (Program $program) {
            AdminHelpers::removeModelImage($program->image);
            AdminHelpers::removeModelImage($program->icon);
            AdminHelpers::removeModelImage($program->cover);

            $program->news()->detach();
        });
    }
}
