<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('sales_closings', function (Blueprint $table) {
            // Asumsi tabel products sudah ada
            $table->foreignId('product_id')
                ->after('sales_target_id')
                ->constrained('products')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('sales_closings', function (Blueprint $table) {
            $table->dropConstrainedForeignId('product_id');
        });
    }
};
