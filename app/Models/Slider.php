<?php

namespace App\Models;

use App\Helpers\AdminHelpers;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model implements TranslatableContract
{
    use HasFactory, Translatable;

    public $translatedAttributes = ['title'];
    protected $fillable = ['slider_location', 'image', 'order', 'status'];

    public function scopeActive(Builder $q) {
        $q->where('status', 1);
    }

    protected static function booted(): void {
        static::deleted(function (Slider $slider) {
            AdminHelpers::removeModelImage($slider->image);
        });
    }
}
