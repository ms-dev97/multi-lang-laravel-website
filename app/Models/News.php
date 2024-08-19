<?php

namespace App\Models;

use App\Helpers\AdminHelpers;
use App\Traits\StatusTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class News extends Model implements TranslatableContract
{
    use HasFactory, Translatable, StatusTrait;

    public $translatedAttributes = ['title', 'excerpt', 'body'];
    protected $fillable = ['slug', 'image', 'gallery', 'featured', 'status'];

    protected $casts = [
        'gallery' => 'array',
    ];

    public function categories(): BelongsToMany {
        return $this->belongsToMany(Category::class, 'category_news', 'news_id', 'category_id');
    }

    public function programs(): BelongsToMany {
        return $this->belongsToMany(Program::class, 'news_program', 'news_id', 'program_id');
    }

    public function projects(): BelongsToMany {
        return $this->belongsToMany(Project::class, 'news_project', 'news_id', 'project_id');
    }

    public function scopeActive(Builder $q) {
        return $q->where('status', 1);
    }

    protected static function booted(): void {
        static::deleted(function (News $news) {
            AdminHelpers::removeModelImage($news->image);

            $news->categories()->detach();
            $news->programs()->detach();
            $news->projects()->detach();
        });
    }
}
