<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('data_konstruksis', function (Blueprint $table) {
            $table->id('id_konstruksi');
            $table->foreignId('pju_id')->constrained('data_pjus', 'id_pju')->onDelete('cascade');
            $table->date('tanggal_penggalian')->nullable();
            $table->date('tanggal_pengecoran')->nullable();
            $table->date('pemasangan_tiang')->nullable();
            $table->date('grounding_finishing')->nullable();
            $table->date('pemasangan_aksesories')->nullable();
            $table->date('pemasangan_mcb')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_konstruksis');
    }
};