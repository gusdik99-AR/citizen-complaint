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
        Schema::table('aduan', function (Blueprint $table) {
            $table->unsignedBigInteger('opd_id')->nullable()->after('akses_aduan_id');
            $table->foreign('opd_id')->references('id')->on('opd')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('aduan', function (Blueprint $table) {
            $table->dropForeign(['opd_id']);
            $table->dropColumn('opd_id');
        });
    }
};
