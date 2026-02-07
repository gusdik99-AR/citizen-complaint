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
        Schema::create('kategori_aduan_opd', function (Blueprint $table) {
            $table->foreignId('kategori_aduan_id')->constrained('kategori_aduan')->onDelete('cascade');
            $table->foreignId('opd_id')->constrained('opd')->onDelete('cascade');
            $table->timestamp('tanggal_dibuat')->useCurrent();
            $table->timestamp('tanggal_diubah')->useCurrent()->useCurrentOnUpdate();

            $table->primary(['kategori_aduan_id', 'opd_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kategori_aduan_opd');
    }
};
