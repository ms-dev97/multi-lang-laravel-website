<?php

namespace App\Models;

use App\Traits\StatusTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gallery extends Model implements TranslatableContract
{
    use HasFactory, Translatable, StatusTrait, SoftDeletes;

    public $translatedAttributes = ['title', 'excerpt', 'body'];
    protected $fillable = ['slug', 'photos','featured', 'status'];

    protected $casts = [
        'photos' => 'array',
    ];

    public function scopeActive(Builder $q) {
        $q->where('status', 1);
    }

    // for search
    protected $appends  = ["showUrl"];

    public function getShowUrlAttribute($value)
    {
        return route('galleries.show', $this);
    }
}
