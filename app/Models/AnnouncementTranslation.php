<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnnouncementTranslation extends Model
{
    use HasFactory;
    protected $table = 'ad_translations';

    protected $fillable = ['title', 'body', 'excerpt'];
    public $timestamps = false;
}
