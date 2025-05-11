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
        Schema::create('kwh_readings', function (Blueprint $table) {
            $table->id('id_reading');
            $table->unsignedBigInteger('panel_id');
            $table->unsignedBigInteger('user_id');
            $table->string('kwh_number');
            $table->string('image_path')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('panel_id')->references('id_panel')->on('data_panels')->onDelete('cascade');
            $table->foreign('user_id')->references('id_user')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kwh_readings');
    }
};