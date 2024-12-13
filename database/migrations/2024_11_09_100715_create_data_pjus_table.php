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
        Schema::create('data_pjus', function (Blueprint $table) {
            $table->id('id_pju');
            $table->foreignId('panel_id')->constrained('data_panels', 'id_panel')->onDelete('cascade');
            $table->string('lapisan')->nullable();
            $table->integer('no_tiang_lama')->nullable();
            // $table->integer('no_tiang_baru');
            $table->integer('no_tiang_baru')->unique();
            $table->string('nama_jalan');
            $table->string('kecamatan');
            $table->integer('tinggi_tiang');
            $table->string('jenis_tiang');
            $table->integer('daya_lampu');
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
        Schema::dropIfExists('data_pjus');
    }
};
