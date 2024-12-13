<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('riwayat_pjus', function (Blueprint $table) {
            $table->id('id_riwayat_pju');
            $table->foreignId('pju_id')->constrained('data_pjus', 'id_pju')->onDelete('cascade');
            $table->string('lokasi')->nullable();
            $table->date('tanggal_masalah')->nullable();
            $table->time('jam_masalah')->nullable();
            $table->string('keterangan_masalah')->nullable();
            $table->string('uraian_masalah')->nullable();
            $table->date('tanggal_penyelesaian')->nullable();
            $table->time('jam_penyelesaian')->nullable();
            $table->string('durasi_penyelesaian')->nullable();
            $table->string('penyelesaian_masalah')->nullable();
            $table->string('pencegahan')->nullable();
            $table->string('nomor_rujukan')->nullable();
            $table->enum('status', ['Pending', 'Selesai', 'Proses'])->default('Pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('riwayat_pjus');
    }
};
