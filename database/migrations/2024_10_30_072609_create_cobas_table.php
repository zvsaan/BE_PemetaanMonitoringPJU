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
        Schema::create('cobas', function (Blueprint $table) {
            $table->id();
            $table->integer('lapisan');
            $table->integer('no_app');
            $table->integer('no_tiang_lama');
            $table->integer('no_tiang_baru');
            $table->string('nama_jalan');
            $table->string('kecamatan');
            $table->float('tinggi_tiang_m');
            $table->string('jenis_tiang');
            $table->integer('daya_lampu_w');
            $table->string('status_jalan');
            $table->decimal('longitude', 10, 7);
            $table->decimal('latitude', 10, 7);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cobas');
    }
};
