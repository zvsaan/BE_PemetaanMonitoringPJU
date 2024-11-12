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
            $table->string('lapisan');
            $table->string('no_app');
            $table->string('no_tiang_lama');
            $table->string('no_tiang_baru');
            $table->string('nama_jalan');
            $table->string('kecamatan');
            $table->decimal('tinggi_tiang', 5, 2);
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
