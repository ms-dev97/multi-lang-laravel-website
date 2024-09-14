<?php

namespace App\Models;

use App\Helpers\AdminHelpers;
use App\Traits\StatusTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Video extends Model implements TranslatableContract
{
    use HasFactory, Translatable, StatusTrait, SoftDeletes;

    public $translatedAttributes = ['title', 'excerpt', 'body'];
    protected $fillable = ['slug', 'image', 'link', 'category_id', 'status', 'featured'];

    public function vidID() {
        preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+(?=\?)|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#", $this->link, $matches);
        return end($matches);
    }

    public function vidImage() {
        return 'https://i.ytimg.com/vi/' . $this->vidID() . '/hqdefault.jpg';
    }

    public function vidFrame() {
        return '<iframe width="100%" height="400"
                src="https://www.youtube.com/embed/' . $this->vidID() . '"
                title="YouTube video player" frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen></iframe>';
    }

    public function scopeActive(Builder $q) {
        $q->where('status', 1);
    }

    protected static function booted(): void {
        static::deleted(function (Video $video) {
            AdminHelpers::removeModelImage($video->image);
        });
    }

    // for search
    protected $appends  = ["showUrl"];

    public function getShowUrlAttribute($value)
    {
        return route('videos.show', $this);
    }
}
