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
        Schema::create('anime_lists', function (Blueprint $table) {
            $table->id();
            $table->integer('mal_id')->unique();
            $table->string('slug')->unique();
            $table->text('title')->nullable();
            $table->string('title_english')->nullable();
            $table->string('title_japanese')->nullable();
            $table->text('synopsis')->nullable();
            $table->text('source')->nullable();
            $table->string('trailer')->nullable();
            $table->string('airing')->nullable();
            $table->string('aired_from')->nullable();
            $table->string('aired_to')->nullable();
            $table->integer('episodes')->nullable();
            $table->string('duration')->nullable();
            $table->string('status')->nullable();
            $table->string('image_url')->nullable();
            $table->string('rating')->nullable();
            $table->float('score', 3, 2)->nullable();
            $table->integer('rank')->nullable();
            $table->string('genres')->nullable();
            $table->string('studio')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anime_lists');
    }
};
