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
        Schema::create('beritas', function (Blueprint $table) {
            $table->id('id_berita');
            $table->string('title');
            $table->text('content');
            $table->string('author')->nullable();
            $table->date('published_date');
            $table->string('image_url')->nullable();
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->string('slug')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('beritas');
    }
};
