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
        Schema::create('aduan', function (Blueprint $table) {
            $table->id();
            $table->string('no_aduan');
            $table->datetime('tanggal_lapor');
            $table->text('isi_aduan');
            $table->string('lokasi');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('foto')->nullable();
            $table->foreignId('masyarakat_id')->constrained('masyarakat')->onDelete('cascade');
            $table->foreignId('kategori_aduan_id')->constrained('kategori_aduan')->onDelete('cascade');
            $table->foreignId('akses_aduan_id')->constrained('akses_aduan')->onDelete('cascade');
            $table->foreignId('status_aduan_id')->constrained('status_aduan')->onDelete('cascade');
            $table->datetime('tanggal_selesai')->nullable();
            $table->timestamp('tanggal_dibuat')->useCurrent();
            $table->timestamp('tanggal_diubah')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aduan');
    }
};
