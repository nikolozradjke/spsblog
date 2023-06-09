<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogCategoryTranslate extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id',
        'title',
        'meta_description',
        'lang'
    ];

    public $timestamps = false;
}
