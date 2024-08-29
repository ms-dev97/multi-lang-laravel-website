<?php

namespace App\Models;

use App\Helpers\AdminHelpers;
use App\Traits\StatusTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class Video extends Model implements TranslatableContract
{
    use HasFactory, Translatable, StatusTrait;

    public $translatedAttributes = ['title', 'excerpt', 'body'];
    protected $fillable = ['slug', 'image', 'link', 'category_id', 'status', 'featured'];

    public function scopeActive(Builder $q) {
        $q->where('status', 1);
    }

    protected static function booted(): void {
        static::deleted(function (Video $video) {
            AdminHelpers::removeModelImage($video->image);
        });
    }
}
