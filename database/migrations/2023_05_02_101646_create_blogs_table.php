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
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('status')->default(0);
            $table->tinyInteger('slider')->default(0);
            $table->date('slider_to')->nullable();
            $table->tinyInteger('main_page')->default(0);
            $table->bigInteger('category_id')->nullable();
            $table->string('image')->nullable();
            $table->string('video')->nullable();
            $table->string('slug')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blogs');
    }
};
