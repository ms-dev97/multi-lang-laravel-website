<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentTranslation extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'excerpt', 'body'];
    public $timestamps = false;
}
