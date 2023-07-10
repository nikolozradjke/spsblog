<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactTranslate extends Model
{
    use HasFactory;

    protected $fillable = ['parent_id', 'logo', 'address', 'lang'];

    public $timestamps = false;
}
