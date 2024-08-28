<?php

namespace App\Models;

use App\Helpers\AdminHelpers;
use App\Traits\StatusTrait;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Document extends Model implements TranslatableContract
{
    use HasFactory, Translatable, StatusTrait;

    public $translatedAttributes = ['title', 'excerpt', 'body'];
    protected $fillable = ['slug', 'path', 'link', 'get_from_link', 'image', 'document_category_id', 'status', 'featured'];

    public function category(): BelongsTo {
        return $this->belongsTo(DocumentCategory::class, 'document_category_id');
    }

    public function scopeActive(Builder $q) {
        $q->where('status', 1);
    }

    protected static function booted(): void {
        static::deleted(function (Document $document) {
            AdminHelpers::removeModelImage($document->image);

            if (!is_null($document->path)) {
                Storage::disk('public')->delete($document->path);
            }
        });
    }
}
