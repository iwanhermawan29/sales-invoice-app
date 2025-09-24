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
        Schema::table('contests', function (Blueprint $table) {
            $table->date('tanggal_mulai')->nullable()->after('periode');
            $table->date('tanggal_selesai')->nullable()->after('tanggal_mulai');
            $table->index(['tanggal_mulai', 'tanggal_selesai']);
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contests', function (Blueprint $table) {
            $table->dropIndex(['tanggal_mulai', 'tanggal_selesai']);
            $table->dropColumn(['tanggal_mulai', 'tanggal_selesai']);
        });
    }
};
