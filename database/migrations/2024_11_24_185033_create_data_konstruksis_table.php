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
            $table->foreignId('panel_id')->constrained('data_panels', 'id_panel')->onDelete('cascade');
            $table->foreignId('pju_id')->constrained('data_pjus', 'id_pju')->onDelete('cascade');
            $table->date('tanggal_penggalian');
            $table->date('tanggal_pengecoran');
            $table->date('pemasangan_tiang');
            $table->date('grounding_finishing');
            $table->date('pemasangan_aksesories');
            $table->date('pemasangan_mcb');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_konstruksis');
    }
};