<?php

namespace App\Models;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class AnnouncementCategory extends Model implements TranslatableContract
{
    use HasFactory, Translatable, SoftDeletes;

    protected $table = 'ad_categories';
    protected $translationForeignKey = 'ad_category_id';

    public $translatedAttributes = ['title'];
    protected $fillable = ['slug', 'featured', 'status'];

    public function announcements(): HasMany {
        return $this->hasMany(Announcement::class, 'ad_category_id');
    }

    public function scopeActive(Builder $q) {
        $q->where('status', 1);
    }
}
