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
        Schema::create('menu', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id_induk')->nullable()->constrained('menu')->onDelete('cascade');
            $table->string('nama_menu');
            $table->string('ikon');
            $table->string('link');
            $table->boolean('status_aktif')->default(true);
            $table->timestamp('tanggal_dibuat')->useCurrent();
            $table->timestamp('tanggal_diubah')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu');
    }
};
