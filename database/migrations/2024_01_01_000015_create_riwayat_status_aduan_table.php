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
        Schema::create('riwayat_status_aduan', function (Blueprint $table) {
            $table->id();
            $table->datetime('waktu_status_aduan');
            $table->text('catatan');
            $table->foreignId('aduan_id')->constrained('aduan')->onDelete('cascade');
            $table->foreignId('status_aduan_id')->constrained('status_aduan')->onDelete('cascade');
            $table->foreignId('pengguna_id')->constrained('pengguna')->onDelete('cascade');
            $table->timestamp('tanggal_dibuat')->useCurrent();
            $table->timestamp('tanggal_diubah')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_status_aduan');
    }
};
