<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Relations\HasMany;

interface ModelColumns
{
    public function getMainColumns() :array;
    public function getTranslateColumns() :array;
    public function content() :HasMany;
}