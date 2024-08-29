<?php

namespace App\Models;

use App\Helpers\AdminHelpers;
use App\Traits\StatusTrait;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model implements TranslatableContract
{
    use HasFactory, Translatable, StatusTrait;

    public $translatedAttributes = ['name', 'excerpt', 'body'];
    protected $fillable = ['slug', 'image', 'has_custom_view', 'view_name', 'status'];

    protected static function booted(): void {
        static::deleted(function (Page $page) {
            AdminHelpers::removeModelImage($page->image);
        });
    }
}
