<?php

namespace App\Models;

use App\Traits\ModelHelper;
use App\Traits\TranslateMedia;
use App\Interfaces\ModelColumns;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Contact extends Model implements ModelColumns
{
    use HasFactory, TranslateMedia;

    use ModelHelper{
        updateItem as protected updateHelper;
    }

    protected $fillable = [
        'zip_code',
        'working_hours',
        'email',
        'phone',
        'facebook',
        'youtube',
        'twitter'
    ];

    private $main_table = 'contacts';
    private $translate_table = 'contact_translates';
    private static $translates_class = 'App\Models\ContactTranslate';
    private static $current_class = __CLASS__;
    private $translate_media = ['logo'];

    public function content() :HasMany
    {
        return $this->hasMany(ContactTranslate::class, 'parent_id', 'id');
    }

    public function getItem($lang = null)
    {
        return $this->with(
                        [
                            'content' => function($query) use($lang){
                                $query->when($lang, function ($q) use($lang){
                                    $q->where('lang', $lang);
                                });
                            }
                        ])
                    ->first();
    }

    public function updateItem($request)
    {
        return $this->updateHelper(
            $request,
            $this->addMedia($request, $this)
        );
    }
}
