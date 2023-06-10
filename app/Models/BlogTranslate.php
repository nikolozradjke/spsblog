<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogTranslate extends Model
{
    use HasFactory;

    protected $fillable = ['parent_id', 'title', 'short_description', 'description', 'lang'];

    public $timestamps = false;
}
