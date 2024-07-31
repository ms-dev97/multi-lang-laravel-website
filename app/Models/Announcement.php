<?php

namespace App\Models;

use App\Helpers\AdminHelpers;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Announcement extends Model implements TranslatableContract
{
    use HasFactory, Translatable;

    protected $table = 'ads';
    protected $translationForeignKey = 'ad_id';

    public $translatedAttributes = ['title', 'excerpt', 'body'];
    protected $fillable = ['image', 'file', 'apply_link', 'ad_category_id', 'featured', 'status'];

    public function category(): BelongsTo {
        return $this->belongsTo(AnnouncementCategory::class, 'ad_category_id');
    }

    public function scopeActive(Builder $q) {
        $q->where('status', 1);
    }

    protected static function booted(): void {
        static::deleted(function (Announcement $announcement) {
            AdminHelpers::removeModelImage($announcement->image);

            if (!is_null($announcement->file)) {
                Storage::disk('public')->delete($announcement->file);
            }
        });
    }
}
