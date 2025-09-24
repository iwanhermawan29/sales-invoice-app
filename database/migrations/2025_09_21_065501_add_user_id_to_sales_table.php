<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            // nullable dulu supaya aman untuk data lama
            $table->foreignId('user_id')->nullable()->after('product_id')
                ->constrained()->nullOnDelete();
            // ↑ nullOnDelete agar kalau user dihapus, sales tidak ikut terhapus (opsional: cascadeOnDelete)
        });
    }
    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
        });
    }
};
