<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnnouncementCategoryTranslation extends Model
{
    use HasFactory;

    protected $table = 'ad_category_translations';
    
    protected $fillable = ['title'];
    public $timestamps = false;
}
