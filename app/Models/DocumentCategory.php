<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentCategory extends Model implements TranslatableContract
{
    use HasFactory, Translatable, SoftDeletes;

    protected $translationForeignKey = 'doc_cat_id';
    public $translatedAttributes = ['title'];
    protected $fillable = ['slug', 'featured', 'status'];

    public function documents(): HasMany {
        return $this->hasMany(Document::class);
    }

    public function scopeActive(Builder $q) {
        $q->where('status', 1);
    }
}
