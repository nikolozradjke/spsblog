<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('menu_translates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id');
            $table->foreign('parent_id')->references('id')->on('menus')->onDelete('cascade');
            $table->string('title');
            $table->text('meta_description')->nullable();
            $table->string('lang');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_translates');
    }
};
